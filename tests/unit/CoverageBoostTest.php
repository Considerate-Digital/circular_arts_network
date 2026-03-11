<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CoverageBoostTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['__can_test_options'] = array();
        $GLOBALS['__can_test_query_vars'] = array();
        $GLOBALS['__can_test_actions'] = array();
        $GLOBALS['__can_test_sent_emails'] = array();
        $GLOBALS['__can_test_current_user'] = (object) array(
            'ID' => 12,
            'user_login' => 'seller12',
            'user_email' => 'seller12@example.com',
            'roles' => array('subscriber'),
        );
        $GLOBALS['__can_test_current_user_id'] = 12;
        $GLOBALS['__can_test_is_singular'] = '';
        $GLOBALS['__can_test_is_archive'] = false;
        $GLOBALS['wp_roles'] = new class {
            public function is_role($role): bool {
                return false;
            }
        };
    }

    public function testLargeHelperDataSourcesAreLoaded(): void
    {
        $currencies = circartsnet_get_all_currencies();
        $icons = circartsnet_get_icons_list();
        $fields = circartsnet_get_listing_fields();

        self::assertArrayHasKey('GBP', $currencies);
        self::assertGreaterThan(100, count($currencies));
        self::assertNotEmpty($icons);
        self::assertNotEmpty($fields);
    }

    public function testSearchQueryBuildsMetaTaxAndOrderingArgs(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_listing_fields'] = array(
            array('key' => 'size', 'type' => 'number'),
            array('key' => 'condition', 'type' => 'text'),
            array('key' => 'seller_id', 'type' => 'text'),
            array('key' => 'status', 'type' => 'text'),
        );

        $args = circartsnet_get_search_query(array(
            'offset' => 5,
            'listing_id' => '99',
            'seller_id' => '12',
            'order' => 'DESC',
            'orderby' => 'price',
            'orderby_custom' => 'condition',
            'tag' => array(2, 3),
            'keywords' => 'paint',
            'size' => '20+',
            'condition' => '!broken',
            'seller_id' => '77',
            'status' => 'used',
            'regular_price' => array('min' => '10', 'max' => '100'),
            'detail_cbs' => array('wheel' => 'yes', 'bag' => 'yes'),
            'lang' => 'en',
        ));

        self::assertSame(5, $args['offset']);
        self::assertSame(array(99), $args['post__in']);
        self::assertSame('meta_value', $args['orderby']);
        self::assertSame('circartsnet_condition', $args['meta_key']);
        self::assertSame('paint', $args['s']);
        self::assertArrayHasKey('tax_query', $args);
        self::assertArrayHasKey('meta_query', $args);
        self::assertGreaterThanOrEqual(5, count($args['meta_query']));
        self::assertContains('wpml_switch_language', array_column($GLOBALS['__can_test_actions'], 'hook'));
    }

    public function testFrontTemplatesMethodSelectsExpectedFiles(): void
    {
        $subject = (new ReflectionClass(CIRCARTSNET_Front_Templates::class))->newInstanceWithoutConstructor();

        $GLOBALS['__can_test_is_singular'] = 'circartsnet_listing';
        $singular = $subject->front_templates('fallback.php');
        self::assertStringContainsString('/templates/single-listing.php', $singular);

        $GLOBALS['__can_test_is_singular'] = '';
        $GLOBALS['__can_test_is_archive'] = true;
        $GLOBALS['__can_test_query_vars']['is_tax'] = 'circartsnet_listing_category';
        $archive = $subject->front_templates('fallback.php');
        self::assertStringContainsString('/templates/archive.php', $archive);
    }

    public function testInitAndRegisterMethodsRun(): void
    {
        $init = (new ReflectionClass(CIRCARTSNET_Init::class))->newInstanceWithoutConstructor();
        $init->circartsnet_options = array('submission_mode' => 'publish');
        $init->circartsnet_load_plugin_textdomain();
        $init->register_role_caps();

        self::assertArrayHasKey('circartsnet_listing_seller', $GLOBALS['__can_test_added_roles']);

        $register = (new ReflectionClass(CIRCARTSNET_Register_CPT::class))->newInstanceWithoutConstructor();
        ob_start();
        $register->render_permalink_settings();
        $html = ob_get_clean();
        self::assertStringContainsString('circartsnet_listing_permalink', (string) $html);
    }

    public function testAdminAndEmailMethodsExecuteAdditionalBranches(): void
    {
        $admin = (new ReflectionClass(CIRCARTSNET_Admin_Settings::class))->newInstanceWithoutConstructor();
        $settingsFields = $admin->admin_settings_fields();
        $builderFields = $admin->get_builder_settings_fields();
        ob_start();
        $admin->render_fields_builder_field_heading('Title', 'Label');
        $heading = ob_get_clean();

        self::assertNotEmpty($settingsFields);
        self::assertNotEmpty($builderFields);
        self::assertStringContainsString('remove-field', (string) $heading);

        $email = (new ReflectionClass(CIRCARTSNET_Email::class))->newInstanceWithoutConstructor();
        $agent = array(
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'username' => 'ada',
            'useremail' => 'ada@example.com',
        );
        $email->seller_registered($agent);
        $email->seller_rejected($agent);
        $email->new_listing_submitted(55);

        self::assertGreaterThanOrEqual(4, count($GLOBALS['__can_test_sent_emails']));
    }
}
