<?php

/**
 * This file contains the DatabaseAccessObjectNoPoolTest class.
 *
 * @package    Lunr\Gravity\Database
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2012-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database\Tests;

use Lunr\Gravity\Database\DatabaseAccessObject;
use Lunr\Halo\PropertyTraits\PsrLoggerTestTrait;

/**
 * This class contains the tests for the DatabaseAccessObject class.
 *
 * Base tests for the case where there is no DatabaseConnectionPool.
 *
 * @covers Lunr\Gravity\Database\DatabaseAccessObject
 */
class DatabaseAccessObjectNoPoolTest extends DatabaseAccessObjectTest
{

    use PsrLoggerTestTrait;

    /**
     * Testcase Constructor.
     */
    public function setUp()
    {
        $this->setUpNoPool();
    }

    /**
     * Test that DatabaseConnection class is passed.
     */
    public function testDatabaseConnectionIsPassed()
    {
        $this->assertPropertySame('db', $this->db);
    }

    /**
     * Test that DatabaseConnection class is passed.
     */
    public function testQueryEscaperIsStored()
    {
        $property = $this->reflection->getProperty('escaper');
        $property->setAccessible(TRUE);

        $this->assertInstanceOf('Lunr\Gravity\Database\DatabaseQueryEscaper', $property->getValue($this->class));
    }

    /**
     * Test that $pool is NULL.
     */
    public function testDatabaseConnectionPoolIsNull()
    {
        $this->assertPropertySame('pool', NULL);
    }

    /**
     * Test that swap_connection() swaps database connections.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::swap_connection
     */
    public function testSwapConnectionSwapsConnection()
    {
        $db = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLConnection')
                   ->disableOriginalConstructor()
                   ->getMock();

        $property = $this->reflection->getProperty('db');
        $property->setAccessible(TRUE);

        $this->assertNotSame($db, $property->getValue($this->class));

        $this->class->swap_connection($db);

        $this->assertSame($db, $property->getValue($this->class));
    }

    /**
     * Test that swap_connection() swaps query escaper.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::swap_connection
     */
    public function testSwapConnectionSwapsQueryEscaper()
    {
        $db = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLConnection')
                   ->disableOriginalConstructor()
                   ->getMock();

        $escaper = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryEscaper')
                        ->disableOriginalConstructor()
                        ->getMock();

        $property = $this->reflection->getProperty('escaper');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->class);

        $db->expects($this->once())
           ->method('get_query_escaper_object')
           ->will($this->returnValue($escaper));

        $this->class->swap_connection($db);

        $new = $property->getValue($this->class);

        $this->assertNotSame($old, $new);
        $this->assertInstanceOf('Lunr\Gravity\Database\DatabaseQueryEscaper', $new);
    }

}

?>
