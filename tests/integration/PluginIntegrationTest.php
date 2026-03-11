<?php
declare(strict_types=1);

class PluginIntegrationTest extends WP_UnitTestCase
{
    public function testCustomPostTypeIsRegisteredOnInit(): void
    {
        do_action('init');
        $post_type = get_post_type_object('circartsnet_listing');

        $this->assertNotNull($post_type);
        $this->assertTrue($post_type->public);
    }

    public function testTaxonomiesAreRegisteredOnInit(): void
    {
        do_action('init');

        $category_tax = get_taxonomy('circartsnet_listing_category');
        $tag_tax = get_taxonomy('circartsnet_listing_tag');

        $this->assertNotNull($category_tax);
        $this->assertNotNull($tag_tax);
        $this->assertTrue($category_tax->public);
        $this->assertTrue($tag_tax->public);
    }

    public function testArchiveTitleUsesCategoryTemplateOption(): void
    {
        update_option('circartsnet_all_settings', array(
            'category_title' => 'Category: %category%',
        ));

        $term = self::factory()->term->create_and_get(array(
            'taxonomy' => 'circartsnet_listing_category',
            'name' => 'Materials',
        ));

        $this->go_to(get_term_link($term));

        $title = apply_filters('get_the_archive_title', get_the_archive_title());
        $this->assertStringContainsString('Category: Materials', $title);
    }
}
