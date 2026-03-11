<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConstructorCoverageTest extends TestCase
{
    public function testConstructorsCanBeInvoked(): void
    {
        $register = new CIRCARTSNET_Register_CPT();
        $init = new CIRCARTSNET_Init();
        $email = new CIRCARTSNET_Email();

        self::assertInstanceOf(CIRCARTSNET_Register_CPT::class, $register);
        self::assertInstanceOf(CIRCARTSNET_Init::class, $init);
        self::assertInstanceOf(CIRCARTSNET_Email::class, $email);
    }
}
