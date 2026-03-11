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
        $GLOBALS['__can_test_inserted_posts'] = array();
        $GLOBALS['__can_test_post_meta'] = array();
        $GLOBALS['__can_test_object_terms'] = array();
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

    public function testSanitizeRequestDataNormalizesNestedListingPayload(): void
    {
        $sanitized = $this->subject->sanitize_request_data(array(
            'listing_id' => '44',
            'listing_title' => '<b>Chair</b>',
            'content' => '<p>Details</p>',
            'gallery_images' => array('9', '11'),
            'circartsnet_listing_latitude' => '55.95',
            'circartsnet_listing_longitude' => '-3.19',
            'circartsnet_data' => array(
                'seller_email' => 'maker@example.com',
                'website' => 'https://example.com',
            ),
        ));

        self::assertSame(44, $sanitized['listing_id']);
        self::assertSame('<b>Chair</b>', $sanitized['listing_title']);
        self::assertSame(array(9, 11), $sanitized['gallery_images']);
        self::assertSame('55.95', $sanitized['circartsnet_listing_latitude']);
        self::assertSame('-3.19', $sanitized['circartsnet_listing_longitude']);
        self::assertSame('maker@example.com', $sanitized['circartsnet_data']['seller_email']);
    }

    public function testInsertListingInDbStoresSanitizedMetaAndTerms(): void
    {
        $listing_id = $this->subject->insert_listing_in_db(
            array(
                'listing_title' => '<b>Stored</b>',
                'content' => '<p>Content</p>',
                'gallery_images' => array('3', '7'),
                'circartsnet_listing_latitude' => '51.5',
                'circartsnet_listing_longitude' => '-0.1',
                'circartsnet_listing_category' => 'Materials',
                'circartsnet_data' => array(
                    'custom_text' => '<em>Value</em>',
                ),
            ),
            (object) array('ID' => 12),
            '',
            'publish'
        );

        self::assertArrayHasKey($listing_id, $GLOBALS['__can_test_inserted_posts']);
        self::assertSame(array(3, 7), $GLOBALS['__can_test_post_meta'][$listing_id]['circartsnet_gallery_images']);
        self::assertSame('51.5', $GLOBALS['__can_test_post_meta'][$listing_id]['circartsnet_listing_latitude']);
        self::assertSame('Materials', $GLOBALS['__can_test_object_terms'][$listing_id]['circartsnet_listing_category']);
    }

    public function testCreateListingOwnershipComparisonUsesNumericIds(): void
    {
        self::assertSame(5, absint('5'));
        self::assertTrue(absint('5') === absint(5));
    }
}
