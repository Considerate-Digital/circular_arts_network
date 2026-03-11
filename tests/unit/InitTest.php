<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class InitTest extends TestCase
{
    private CIRCARTSNET_Init $subject;

    protected function setUp(): void
    {
        $ref = new ReflectionClass(CIRCARTSNET_Init::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
        $this->subject->circartsnet_options = array('submission_mode' => 'publish');
        $GLOBALS['__can_test_current_user_id'] = 0;
        $GLOBALS['__can_test_user_caps'] = array();
    }

    public function testDisableGutenbergForListingsOnly(): void
    {
        self::assertFalse($this->subject->disable_gutenberg(true, 'circartsnet_listing'));
        self::assertTrue($this->subject->disable_gutenberg(true, 'post'));
    }

    public function testShowCurrentUserAttachmentsRestrictsAuthorForNonAdmins(): void
    {
        $GLOBALS['__can_test_current_user_id'] = 7;
        $GLOBALS['__can_test_user_caps'] = array();

        $result = $this->subject->show_current_user_attachments(array('post_status' => 'inherit'));

        self::assertSame(7, $result['author']);
    }

    public function testShowCurrentUserAttachmentsDoesNotRestrictAdmins(): void
    {
        $GLOBALS['__can_test_current_user_id'] = 7;
        $GLOBALS['__can_test_user_caps'] = array('activate_plugins');

        $result = $this->subject->show_current_user_attachments(array('post_status' => 'inherit'));

        self::assertArrayNotHasKey('author', $result);
    }

    public function testAllowAttachmentActionsGrantsRequestedCapability(): void
    {
        $GLOBALS['__can_test_post'] = (object) array('post_type' => 'attachment');
        $caps = array('edit_post' => false);

        $result = $this->subject->allow_attachment_actions($caps, array('edit_post'), array(0, 0, 123));

        self::assertTrue($result['edit_post']);
    }
}
