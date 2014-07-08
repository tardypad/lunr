<?php

/**
 * This file contains the MailLoggerTest class.
 *
 * PHP Version 5.5
 *
 * @category   Libraries
 * @package    Feedback
 * @subpackage Tests
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 * @copyright  2012-2014, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Feedback\Tests;

use Lunr\Feedback\MailLogger;
use Psr\Log\LogLevel;
use Lunr\Halo\LunrBaseTest;
use ReflectionClass;

/**
 * This class contains test methods for the MailLogger class.
 *
 * @category   Libraries
 * @package    Feedback
 * @subpackage Tests
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 * @covers     Lunr\Feedback\MailLogger
 */
abstract class MailLoggerTest extends LunrBaseTest
{

    /**
     * Mock-instance of the Request class.
     * @var Request
     */
    protected $request;

    /**
     * Mock instance of the Mail class
     * @var Mail
     */
    protected $mail;

    /**
     * Request host used for the logging output
     * @var String
     */
    const REQUEST_HOST = 'host.local';

    /**
     * Request call used for the logging output
     * @var String
     */
    const REQUEST_CALL = 'controller/method';

    /**
     * Testcase Constructor.
     */
    public function setUp()
    {
        $this->reflection = new ReflectionClass('Lunr\Feedback\MailLogger');

        $this->request       = $this->getMock('Lunr\Corona\RequestInterface');
        $this->request->host = self::REQUEST_HOST;
        $this->request->call = self::REQUEST_CALL;

        $this->mail = $this->getMock('Lunr\Network\Mail');

        $this->class = new MailLogger('', '', $this->request, $this->mail);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown()
    {
        unset($this->request);
        unset($this->reflection);
        unset($this->mail);
        unset($this->class);
    }

    /**
     * Unit test data provider for log messages.
     *
     * @return array $messages Array of log messages
     */
    public function messageProvider()
    {
        $messages   = array();
        $messages[] = array('msg', array('test' => 'value'), 'msg');
        $messages[] = array('{test} msg', array('test' => 'value'), 'value msg');
        $messages[] = array('{test} msg, {test1}', array('test' => 'value', 'test1' => 1), 'value msg, 1');
        $messages[] = array('{test} msg', array('test' => 'value', 'file' => 'file.php', 'line' => 11), 'value msg (file.php: 11)');

        return $messages;
    }

    /**
     * Unit test data provider for log subject.
     *
     * @return array $messages Array of subject messages
     */
    public function subjectProvider()
    {
        $subjects   = array();
        $subjects[] = array('warning', NULL, NULL, 'WARNING');
        $subjects[] = array('warning', 'host.local', NULL, 'WARNING host.local');
        $subjects[] = array('warning', NULL, 'controller/method', 'WARNING controller/method');
        $subjects[] = array('warning', 'host.local', 'controller/method', 'WARNING host.local controller/method');

        return $subjects;
    }

}

?>
