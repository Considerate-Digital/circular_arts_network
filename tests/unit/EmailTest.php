<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    private CIRCARTSNET_Email $subject;

    protected function setUp(): void
    {
        $ref = new ReflectionClass(CIRCARTSNET_Email::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
        $GLOBALS['__can_test_options'] = array();
        $GLOBALS['__can_test_actions'] = array();
        $GLOBALS['__can_test_sent_emails'] = array();
        $GLOBALS['__can_test_wp_mail_result'] = true;
    }

    public function testSendEmailBuildsHeadersAndFormatsBody(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'email_br' => 'enable',
        );

        $this->subject->send_email('user@example.com', 'Subject', "Line 1\nLine 2");

        self::assertCount(1, $GLOBALS['__can_test_sent_emails']);
        $email = $GLOBALS['__can_test_sent_emails'][0];
        self::assertSame('user@example.com', $email['to']);
        self::assertStringContainsString('From:', implode("\n", $email['headers']));
        self::assertStringContainsString('<br', $email['message']);
    }

    public function testSellerApprovedReplacesTemplateTokensAndSendsEmail(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'to_seller_approved' => 'Hi %first_name% %last_name% (%username%) <%seller_email%>',
        );

        $this->subject->seller_approved(array(
            'username' => 'maker01',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'useremail' => 'ada@example.com',
        ));

        self::assertCount(1, $GLOBALS['__can_test_sent_emails']);
        $email = $GLOBALS['__can_test_sent_emails'][0];
        self::assertSame('ada@example.com', $email['to']);
        self::assertStringContainsString('Ada Lovelace (maker01) <ada@example.com>', $email['message']);
        self::assertStringContainsString('Approved', $email['subject']);
    }

    public function testNewListingApprovedIncludesListingPlaceholders(): void
    {
        $GLOBALS['__can_test_options']['circartsnet_all_settings'] = array(
            'to_seller_submission_approved' => 'ID:%listing_id% TITLE:%listing_title% URL:%listing_url%',
        );

        $this->subject->new_listing_approved(77);

        self::assertCount(1, $GLOBALS['__can_test_sent_emails']);
        $email = $GLOBALS['__can_test_sent_emails'][0];
        self::assertSame('seller@example.com', $email['to']);
        self::assertStringContainsString('ID:77', $email['message']);
        self::assertStringContainsString('TITLE:Listing #77', $email['message']);
        self::assertStringContainsString('https://example.com/?p=77', $email['message']);
    }

}
