<?php

/**
 * This file contains the MPNSDispatcherTest class.
 *
 * PHP Version 5.4
 *
 * @package    Lunr\Vortex\MPNS
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2013-2014, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Vortex\MPNS\Tests;

use Lunr\Vortex\MPNS\MPNSDispatcher;
use Lunr\Vortex\MPNS\MPNSPriority;
use Lunr\Vortex\MPNS\MPNSType;
use Lunr\Halo\LunrBaseTest;
use ReflectionClass;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the MPNSDispatcher class.
 *
 * @covers Lunr\Vortex\MPNS\MPNSDispatcher
 */
abstract class MPNSDispatcherTest extends LunrBaseTest
{

    /**
     * Mock instance of the Curl class.
     * @var \Lunr\Network\Curl
     */
    protected $curl;

    /**
     * Mock instance of a Logger class.
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of a Header class.
     * @var \http\Header
     */
    protected $header;

    /**
     * Testcase Constructor.
     */
    public function setUp()
    {
        $this->curl = $this->getMockBuilder('Lunr\Network\Curl')
                           ->disableOriginalConstructor()
                           ->getMock();

        $this->logger = $this->getMock('Psr\Log\LoggerInterface');

        $this->header = $this->getMock('http\Header');

        $this->class = new MPNSDispatcher($this->curl, $this->logger, $this->header);

        $this->reflection = new ReflectionClass('Lunr\Vortex\MPNS\MPNSDispatcher');
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown()
    {
        unset($this->class);
        unset($this->reflection);
        unset($this->curl);
        unset($this->logger);
        unset($this->header);
    }

    /**
     * Unit test data provider for valid MPNS Priorities.
     *
     * @return array $priorities Array of MPNS priorities.
     */
    public function validPriorityProvider()
    {
        $priorities   = [];
        $priorities[] = [ MPNSPriority::TILE_IMMEDIATELY ];
        $priorities[] = [ MPNSPriority::TOAST_IMMEDIATELY ];
        $priorities[] = [ MPNSPriority::RAW_IMMEDIATELY ];
        $priorities[] = [ MPNSPriority::TILE_WAIT_450 ];
        $priorities[] = [ MPNSPriority::TOAST_WAIT_450 ];
        $priorities[] = [ MPNSPriority::RAW_WAIT_450 ];
        $priorities[] = [ MPNSPriority::TILE_WAIT_900 ];
        $priorities[] = [ MPNSPriority::TOAST_WAIT_900 ];
        $priorities[] = [ MPNSPriority::RAW_WAIT_900 ];

        return $priorities;
    }

    /**
     * Unit test data provider for valid MPNS Types.
     *
     * @return array $types Array of MPNS types.
     */
    public function validTypeProvider()
    {
        $types   = [];
        $types[] = [ MPNSType::TILE ];
        $types[] = [ MPNSType::TOAST ];
        $types[] = [ MPNSType::RAW ];

        return $types;
    }

}

?>
