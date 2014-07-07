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
     * Mock-instance of the DateTime class.
     * @var DateTime
     */
    protected $datetime;

    /**
     * Mock instance of the Mail class
     * @var Mail
     */
    protected $mail;

    /**
     * DateTime string used for Logging Output.
     * @var String
     */
    const DATETIME_STRING = '2011-11-10 10:30:22';

    /**
     * Request host used for the logging output
     * @var String
     */
    const REQUEST_HOST = 'host.local';

    /**
     * Testcase Constructor.
     */
    public function setUp()
    {
        $this->reflection = new ReflectionClass('Lunr\Feedback\MailLogger');

        $this->request       = $this->getMock('Lunr\Corona\RequestInterface');
        $this->request->host = self::REQUEST_HOST;

        $this->datetime = $this->getMock('Lunr\Core\DateTime');

        $this->datetime->expects($this->once())
                       ->method('set_datetime_format')
                       ->with($this->equalTo('%Y-%m-%d %H:%M:%S'));

        $this->mail = $this->getMock('Lunr\Network\Mail');

        $this->class = new MailLogger(array(), $this->datetime, $this->request, $this->mail);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown()
    {
        unset($this->request);
        unset($this->datetime);
        unset($this->reflection);
        unset($this->mail);
        unset($this->class);
    }

}

?>
