<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['__can_test_options'] = array();
    }

    public function testColumnClassesMapping(): void
    {
        self::assertSame('col-sm-12', circartsnet_get_column_classes('1'));
        self::assertSame('col-sm-6', circartsnet_get_column_classes('2'));
        self::assertSame('col-sm-4', circartsnet_get_column_classes('3'));
        self::assertSame('col-sm-3', circartsnet_get_column_classes('4'));
        self::assertSame('col', circartsnet_get_column_classes('9'));
    }

    public function testDefaultSectionCheck(): void
    {
        self::assertTrue(circartsnet_is_default_section(array('key' => 'description')));
        self::assertTrue(circartsnet_is_default_section(array('key' => 'gallery_images')));
        self::assertFalse(circartsnet_is_default_section(array('key' => 'category')));
    }

    public function testPriceFormatFromOption(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'currency_position' => 'right_space',
        );

        self::assertSame('%2$s&nbsp;%1$s', circartsnet_get_price_format());
    }

    public function testPriceHelpersUseDefaultsWhenMissing(): void
    {
        self::assertSame('.', circartsnet_get_price_decimal_separator());
        self::assertSame('', circartsnet_get_price_thousand_separator());
        self::assertSame(2, circartsnet_get_price_decimals());
    }

    public function testCurrencySymbolLookupFallsBackToEmptyForUnknownCode(): void
    {
        self::assertSame('&pound;', circartsnet_get_currency_symbol('GBP'));
        self::assertSame('', circartsnet_get_currency_symbol('INVALID'));
    }

    public function testListingPriceIncludesCurrencyAndFormattedAmount(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'currency' => 'GBP',
            'currency_position' => 'left',
            'decimal_points' => '2',
            'decimal_separator' => '.',
            'thousand_separator' => ',',
        );

        $price = circartsnet_get_listing_price(1234.5);

        self::assertStringContainsString('can-currency-symbol', $price);
        self::assertStringContainsString('1,234.50', $price);
    }

    public function testLeafletProviderReturnsExpectedShape(): void
    {
        $provider = circartsnet_get_leaflet_provider('1');

        self::assertIsArray($provider);
        self::assertArrayHasKey('provider', $provider);
        self::assertStringContainsString('openstreetmap.org', $provider['provider']);
    }
}
