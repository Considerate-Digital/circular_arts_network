<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ShortcodesTest extends TestCase
{
    private CIRCARTSNET_Shortcodes $subject;

    protected function setUp(): void
    {
        $ref = new ReflectionClass(CIRCARTSNET_Shortcodes::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
        $GLOBALS['__can_test_query_vars'] = array();
        $GLOBALS['__can_test_current_user_id'] = 0;
        $GLOBALS['__can_test_current_user'] = (object) array('ID' => 0, 'roles' => array());
    }

    public function testGetListingsQueryArgsSupportsPriceSortingAndIdFilters(): void
    {
        $args = $this->subject->get_listings_query_args(array(
            'orderby' => 'price',
            'ids' => '10,20',
            'exclude' => '2,3',
            'admin_status' => 'publish,draft',
        ));

        self::assertSame('meta_value_num', $args['orderby']);
        self::assertSame('circartsnet_regular_price', $args['meta_key']);
        self::assertSame(array('10', '20'), $args['post__in']);
        self::assertSame(array('2', '3'), $args['post__not_in']);
        self::assertSame(array('publish', 'draft'), $args['post_status']);
    }

    public function testGetListingsQueryArgsUsesCurrentUserWhenRequested(): void
    {
        $GLOBALS['__can_test_current_user_id'] = 23;
        $GLOBALS['__can_test_current_user'] = (object) array('ID' => 23, 'roles' => array('subscriber'));

        $args = $this->subject->get_listings_query_args(array(
            'author' => 'current',
        ));

        self::assertSame(23, $args['author']);
    }

    public function testGetListingsQueryArgsBuildsTaxQueryForCategories(): void
    {
        $args = $this->subject->get_listings_query_args(array(
            'categories' => 'Materials, Equipment',
        ));

        self::assertArrayHasKey('tax_query', $args);
        self::assertSame('circartsnet_listing_category', $args['tax_query'][0]['taxonomy']);
        self::assertSame(array('Materials', 'Equipment'), $args['tax_query'][0]['terms']);
    }
}
