<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AdminSettingsTest extends TestCase
{
    private CIRCARTSNET_Admin_Settings $subject;

    protected function setUp(): void
    {
        $ref = new ReflectionClass(CIRCARTSNET_Admin_Settings::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
        $GLOBALS['__can_test_options'] = array();
        $GLOBALS['__can_test_actions'] = array();
        $GLOBALS['__can_test_query_vars'] = array();
    }

    public function testGetSettingsFieldAllowedHtmlContainsExpectedTagRules(): void
    {
        $allowed = $this->subject->get_settings_field_allowed_html();

        self::assertArrayHasKey('input', $allowed);
        self::assertArrayHasKey('textarea', $allowed);
        self::assertArrayHasKey('button', $allowed);
        self::assertArrayHasKey('type', $allowed['input']);
    }

    public function testGetListingFieldTypesContainsCoreTypes(): void
    {
        $types = $this->subject->get_listing_field_types();

        self::assertArrayHasKey('text', $types);
        self::assertArrayHasKey('price', $types);
        self::assertArrayHasKey('shortcode', $types);
    }

    public function testGetSectionAccessibilitiesContainsAllExpectedModes(): void
    {
        $access = $this->subject->get_section_accessibilities();

        self::assertArrayHasKey('public', $access);
        self::assertArrayHasKey('seller', $access);
        self::assertArrayHasKey('registered', $access);
        self::assertArrayHasKey('admin', $access);
        self::assertArrayHasKey('disable', $access);
    }

    public function testGetFieldsSectionsReturnsDefaultsWhenOptionMissing(): void
    {
        $sections = $this->subject->get_fields_sections();

        self::assertNotEmpty($sections);
        self::assertSame('description', $sections[0]['key']);
        self::assertTrue(in_array('category', array_column($sections, 'key'), true));
    }

    public function testCustomWpksesPostTagsAddsIframeForPostContext(): void
    {
        $result = $this->subject->custom_wpkses_post_tags(array(), 'post');
        self::assertArrayHasKey('iframe', $result);
        self::assertArrayHasKey('src', $result['iframe']);
    }

    public function testListingSubmissionEmailTriggersEventsForPendingAndPublishTransitions(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'listing_submission_mode' => 'approve',
        );
        $listing = (object) array(
            'ID' => 91,
            'post_type' => 'circartsnet_listing',
        );

        $this->subject->listing_submission_email('pending', 'draft', $listing);
        $this->subject->listing_submission_email('publish', 'pending', $listing);

        $hooks = array_column($GLOBALS['__can_test_actions'], 'hook');
        self::assertContains('circartsnet_new_listing_submitted', $hooks);
        self::assertContains('circartsnet_new_listing_approved', $hooks);
    }

    public function testArchivePageListingsCountSetsPostsPerPageOnListingTaxonomies(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'listings_per_page' => 15,
        );
        $GLOBALS['__can_test_query_vars']['is_tax'] = 'circartsnet_listing_tag';

        $query = new class {
            public array $sets = array();
            public function is_main_query(): bool {
                return true;
            }
            public function set(string $key, $value): void {
                $this->sets[$key] = $value;
            }
        };

        $this->subject->archive_page_listings_count($query);

        self::assertEquals(15, $query->sets['posts_per_page']);
    }
}
