<?php

/**
 * This file contains the MailLoggerBaseTest class.
 *
 * PHP Version 5.5
 *
 * @category   Libraries
 * @package    Feedback
 * @subpackage Tests
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 * @copyright  2014, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Feedback\Tests;

use Psr\Log\LogLevel;

/**
 * This class contains test methods for the MailLogger class.
 *
 * @category   Libraries
 * @package    Feedback
 * @subpackage Tests
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 * @covers     Lunr\Feedback\MailLogger
 */
class MailLoggerBaseTest extends MailLoggerTest
{

    /**
     * Test that the request class is set correctly.
     */
    public function testRequestSetCorrectly()
    {
        $this->assertPropertySame('request', $this->request);
    }

    /**
     * Test that the mail class is set correctly.
     */
    public function testMailSetCorrectly()
    {
        $this->assertPropertySame('mail', $this->mail);
    }

    /**
     * Test that set_mail_from() defines correctly the 'from' field of the mail.
     *
     * @covers Lunr\Feedback\MailLogger::set_mail_from
     */
    public function testSetMailFrom()
    {
        $this->set_reflection_property_value('from', 'test@m2mobi.com');

        $this->mail->expects($this->once())
                   ->method('set_from')
                   ->with('test@m2mobi.com');

        $method = $this->get_accessible_reflection_method('set_mail_from');
        $method->invoke($this->class);
    }

    /**
     * Test that set_mail_to() defines correctly the unique 'to' field of the mail.
     *
     * @covers Lunr\Feedback\MailLogger::set_mail_to
     */
    public function testSetMailToUnique()
    {
        $this->set_reflection_property_value('to', 'test@m2mobi.com');

        $this->mail->expects($this->once())
                   ->method('add_to')
                   ->with('test@m2mobi.com');

        $method = $this->get_accessible_reflection_method('set_mail_to');
        $method->invoke($this->class);
    }

    /**
     * Test that set_mail_to() defines correctly multiple 'to' fields in the mail.
     *
     * @covers Lunr\Feedback\MailLogger::set_mail_to
     */
    public function testSetMailToMultiple()
    {
        $this->set_reflection_property_value('to', array('test@m2mobi.com', 'test2@m2mobi.com'));

        $this->mail->expects($this->at(0))
                   ->method('add_to')
                   ->with('test@m2mobi.com');

        $this->mail->expects($this->at(1))
                   ->method('add_to')
                   ->with('test2@m2mobi.com');

        $this->mail->expects($this->exactly(2))
                   ->method('add_to');

        $method = $this->get_accessible_reflection_method('set_mail_to');
        $method->invoke($this->class);
    }

    /**
     * Test the compose_message() function.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @dataProvider messageProvider
     * @covers       Lunr\Feedback\MailLogger::compose_message
     */
    public function testComposeMessage($message, $context, $expected)
    {
        $method = $this->get_accessible_reflection_method('compose_message');

        $msg = $method->invokeArgs($this->class, array($message, $context));

        $this->assertEquals($expected, $msg);
    }

    /**
     * Test the compose_subject() function.
     *
     * @param String $level    Log level
     * @param String $host     Request host
     * @param String $call     Request call
     * @param String $expected Expected log subject
     *
     * @dataProvider subjectProvider
     * @covers       Lunr\Feedback\MailLogger::compose_subject
     */
    public function testComposeSubject($level, $host, $call, $expected)
    {
        $this->request->host = $host;
        $this->request->call = $call;

        $method = $this->get_accessible_reflection_method('compose_subject');

        $subject = $method->invokeArgs($this->class, array($level));

        $this->assertEquals($expected, $subject);
    }

    /**
     * Test that log() logs correctly to a mail if configuration is valid.
     *
     * @covers Lunr\Feedback\MailLogger::log
     */
    public function testLogSendsMessageInMail()
    {
        $this->set_reflection_property_value('is_configuration_valid', TRUE);

        $this->mail->expects($this->once())
                   ->method('set_subject')
                   ->with('WARNING ' . self::REQUEST_HOST . ' ' . self::REQUEST_CALL)
                   ->will($this->returnSelf());

        $this->mail->expects($this->once())
                   ->method('set_message')
                   ->with('Foo')
                   ->will($this->returnSelf());

        $this->mail->expects($this->once())
                   ->method('send');

        $this->class->log(LogLevel::WARNING, 'Foo');
    }

    /**
     * Test that log() doesn't send a mail if configuration is invalid.
     *
     * @covers Lunr\Feedback\MailLogger::log
     */
    public function testLogDontSendsMessageInMail()
    {
        $this->set_reflection_property_value('is_configuration_valid', FALSE);

        $this->mail->expects($this->never())
                   ->method('set_subject');

        $this->mail->expects($this->never())
                   ->method('set_message');

        $this->mail->expects($this->never())
                   ->method('send');

        $this->class->log(LogLevel::WARNING, 'Foo');
    }

}

?>
