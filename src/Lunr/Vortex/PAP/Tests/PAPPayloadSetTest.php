<?php

/**
 * This file contains the PAPPayloadSetTest class.
 *
 * @package    Lunr\Vortex\PAP
 * @author     Leonidas Diamantis <leonidas@m2mobi.com>
 * @copyright  2013-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Vortex\PAP\Tests;

/**
 * This class contains tests for the setters of the PAPPayload class.
 *
 * @covers Lunr\Vortex\PAP\PAPPayload
 */
class PAPPayloadSetTest extends PAPPayloadTest
{

    /**
     * Test set_message_data() works correctly.
     *
     * @covers Lunr\Vortex\PAP\PAPPayload::set_message_data
     */
    public function testSetMessageData()
    {
        $this->class->set_message_data('key', 'value');

        $value = $this->get_reflection_property_value('data');

        $this->assertArrayHasKey('key', $value);
        $this->assertEquals([ 'key' => 'value' ], $value);
    }

    /**
     * Test fluid interface of set_message_data().
     *
     * @covers Lunr\Vortex\PAP\PAPPayload::set_message_data
     */
    public function testSetMessageDataReturnsSelfReference()
    {
        $this->assertSame($this->class, $this->class->set_message_data('key', 'value'));
    }

    /**
     * Test that set_deliver_before_timestamp() sets the deliverbefore.
     *
     * @covers Lunr\Vortex\PAP\PAPPayload::set_priority
     */
    public function testSetPrioritySetsPriority()
    {
        $priority = 'priority';
        $this->class->set_priority($priority);

        $this->assertPropertyEquals('priority', 'priority');
    }

    /**
     * Test the fluid interface of set_deliver_before_timestamp().
     *
     * @covers Lunr\Vortex\PAP\PAPPayload::set_priority
     */
    public function testSetPriorityReturnsSelfReference()
    {
        $cid = 'cid';
        $this->assertEquals($this->class, $this->class->set_priority($cid));
    }

}

?>
