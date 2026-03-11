<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RegisterCptTest extends TestCase
{
    private CIRCARTSNET_Register_CPT $subject;

    protected function setUp(): void
    {
        $ref = new ReflectionClass(CIRCARTSNET_Register_CPT::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
        $GLOBALS['__can_test_query_vars'] = array();
        $GLOBALS['__can_test_registered_post_types'] = array();
        $GLOBALS['__can_test_registered_taxonomies'] = array();
    }

    public function testAuthorOverrideReplacesDropdownForListingPostType(): void
    {
        global $post, $user_ID;

        $post = (object) array(
            'ID' => 33,
            'post_author' => 12,
            'post_type' => 'circartsnet_listing',
        );
        $user_ID = 99;

        $result = $this->subject->author_override('<select name="post_author_override"></select>');

        self::assertStringContainsString('post_author_override', $result);
        self::assertStringNotContainsString('post_author_override_replaced', $result);
    }

    public function testListingMessagesAddsViewAndPreviewLinks(): void
    {
        $GLOBALS['__can_test_post'] = (object) array(
            'ID' => 42,
            'post_type' => 'circartsnet_listing',
            'post_date' => '2026-03-11 10:00:00',
        );

        $messages = $this->subject->listing_messages(array());

        self::assertArrayHasKey('circartsnet_listing', $messages);
        self::assertStringContainsString('View Listing', $messages['circartsnet_listing'][1]);
        self::assertStringContainsString('Preview Listing', $messages['circartsnet_listing'][8]);
    }

    public function testRegisterCreatesPostTypeAndTaxonomies(): void
    {
        $this->subject->register();

        self::assertArrayHasKey('circartsnet_listing', $GLOBALS['__can_test_registered_post_types']);
        self::assertArrayHasKey('circartsnet_listing_category', $GLOBALS['__can_test_registered_taxonomies']);
        self::assertArrayHasKey('circartsnet_listing_tag', $GLOBALS['__can_test_registered_taxonomies']);
    }
}
