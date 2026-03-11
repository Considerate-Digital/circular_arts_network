<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FrontTemplatesTest extends TestCase
{
    private CIRCARTSNET_Front_Templates $subject;

    protected function setUp(): void
    {
        $ref = new ReflectionClass(CIRCARTSNET_Front_Templates::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
        $GLOBALS['__can_test_query_vars'] = array();
        $GLOBALS['__can_test_options'] = array();
    }

    public function testSortingOptionsContainsExpectedDefaultEntries(): void
    {
        $options = $this->subject->lists_sorting_options();
        self::assertCount(4, $options);
        self::assertSame('date-desc', $options[0]['value']);
    }

    public function testStatusOptionsContainsExpectedDefaultEntries(): void
    {
        $options = $this->subject->lists_status_options();
        self::assertCount(4, $options);
        self::assertSame('publish', $options[1]['value']);
    }

    public function testCustomArchiveTitleUsesConfiguredCategoryTemplate(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'category_title' => 'Browse %category%',
        );
        $GLOBALS['__can_test_query_vars']['is_tax'] = 'circartsnet_listing_category';
        $GLOBALS['__can_test_query_vars']['single_cat_title'] = 'Materials';

        $title = $this->subject->custom_archive_title('ignored');

        self::assertSame('Browse Materials', $title);
    }

    public function testGetCategoryNameReturnsFirstTermLowercased(): void
    {
        $GLOBALS['__can_test_query_vars']['terms'] = array(
            (object) array('name' => 'Equipment'),
            (object) array('name' => 'Materials'),
        );

        self::assertSame('equipment', $this->subject->get_category_name(10));
    }
}
