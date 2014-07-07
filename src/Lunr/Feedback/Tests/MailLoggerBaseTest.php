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
     * Test that the datetime class is set correctly.
     */
    public function testDatetimeSetCorrectly()
    {
        $this->assertPropertySame('datetime', $this->datetime);
    }

    /**
     * Test that the mail class is set correctly.
     */
    public function testMailSetCorrectly()
    {
        $this->assertPropertySame('mail', $this->mail);
    }

    /**
     * Test that set_mail_from() don't do anything if no 'from' address is defined.
     *
     * @covers Lunr\Feedback\MailLogger::set_mail_from
     */
    public function testSetMailFromEmpty()
    {
        $this->set_reflection_property_value('parameters', array());

        $this->mail->expects($this->never())
                   ->method('set_from');

        $method = $this->get_accessible_reflection_method('set_mail_from');
        $method->invoke($this->class);
    }

    /**
     * Test that set_mail_from() defines correctly the 'from' field of the mail.
     *
     * @covers Lunr\Feedback\MailLogger::set_mail_from
     */
    public function testSetMailFrom()
    {
        $this->set_reflection_property_value('parameters',
            array('from' => 'test@m2mobi.com')
        );

        $this->mail->expects($this->once())
                   ->method('set_from')
                   ->with('test@m2mobi.com');

        $method = $this->get_accessible_reflection_method('set_mail_from');
        $method->invoke($this->class);
    }

    /**
     * Test that set_mail_to() don't do anything if no 'to' address is defined.
     *
     * @covers Lunr\Feedback\MailLogger::set_mail_to
     */
    public function testSetMailToEmpty()
    {
        $this->set_reflection_property_value('parameters', array());

        $this->mail->expects($this->never())
                   ->method('add_to');

        $method = $this->get_accessible_reflection_method('set_mail_to');
        $method->invoke($this->class);
    }

    /**
     * Test that set_mail_to() defines correctly the unique 'to' field of the mail.
     *
     * @covers Lunr\Feedback\MailLogger::set_mail_to
     */
    public function testSetMailToUnique()
    {
        $this->set_reflection_property_value('parameters',
            array('to' => 'test@m2mobi.com')
        );

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
        $this->set_reflection_property_value('parameters',
            array('to' => array('test@m2mobi.com', 'test2@m2mobi.com'))
        );

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
     * Test that log() logs correctly to a mail.
     *
     * @covers Lunr\Feedback\MailLogger::log
     */
    public function testLogSendsMessageInMail()
    {
        $this->datetime->expects($this->once())
                       ->method('get_datetime')
                       ->will($this->returnValue(self::DATETIME_STRING));

        $this->mail->expects($this->once())
                   ->method('set_subject')
                   ->with('WARNING ' . self::REQUEST_HOST . ' [' . self::DATETIME_STRING . ']')
                   ->will($this->returnSelf());

        $this->mail->expects($this->once())
                   ->method('set_message')
                   ->with('Foo')
                   ->will($this->returnSelf());

        $this->mail->expects($this->once())
                   ->method('send');

        $this->class->log(LogLevel::WARNING, 'Foo');
    }

}

?>
