<?php

/**
 * This file contains the SQLite3ConnectionTransactionTest class.
 *
 * @package    Lunr\Gravity\Database\SQLite3
 * @author     Dinos Theodorou <dinos@m2mobi.com>
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2013-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database\SQLite3\Tests;

use Lunr\Gravity\Database\SQLite3\SQLite3Connection;
use ReflectionClass;

/**
 * This class contains transaction related unit tests for SQLite3Connection.
 *
 * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection
 */
class SQLite3ConnectionTransactionTest extends SQLite3ConnectionTest
{

    /**
     * Test a successful call to Begin Transaction.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::begin_transaction
     */
    public function testBeginTransactionStartsTransactionWhenConnected()
    {
        $this->set_reflection_property_value('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('exec')
                      ->with($this->equalTo('BEGIN TRANSACTION'))
                      ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->begin_transaction());
    }

    /**
     * Test a call to Begin Transaction with no connection.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::begin_transaction
     */
    public function testBeginTransactionThrowsExceptionWhenNotConnected()
    {
        $this->set_reflection_property_value('connected', FALSE);

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->will($this->returnValue(1));

        $this->sqlite3->expects($this->never())
                      ->method('exec');

        $this->expectException('Lunr\Gravity\Database\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->begin_transaction();
    }

    /**
     * Test a successful call to Commit.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::commit
     */
    public function testCommitWhenConnected()
    {
        $this->set_reflection_property_value('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('exec')
                      ->with($this->equalTo('COMMIT TRANSACTION'))
                      ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->commit());
    }

    /**
     * Test a call to Commit with no connection.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::commit
     */
    public function testCommitThrowsExceptionWhenNotConnected()
    {
        $this->set_reflection_property_value('connected', FALSE);

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->will($this->returnValue(1));

        $this->sqlite3->expects($this->never())
                      ->method('exec');

        $this->expectException('Lunr\Gravity\Database\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->commit();
    }

    /**
     * Test a successful call to rollback.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::rollback
     */
    public function testRollbackWhenConnected()
    {
        $this->set_reflection_property_value('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('exec')
                      ->with($this->equalTo('ROLLBACK TRANSACTION'))
                      ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->rollback());
    }

    /**
     * Test a call to Commit with no connection.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::commit
     */
    public function testRollbackThrowsExceptionWhenNotConnected()
    {
        $this->set_reflection_property_value('connected', FALSE);

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->will($this->returnValue(1));

        $this->sqlite3->expects($this->never())
                      ->method('exec');

        $this->expectException('Lunr\Gravity\Database\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->rollback();
    }

    /**
     * Test a successful call to rollback.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::end_transaction
     */
    public function testEndTransactionWhenConnected()
    {
        $this->set_reflection_property_value('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('exec')
                      ->with($this->equalTo('END TRANSACTION'))
                      ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->end_transaction());
    }

    /**
     * Test a call to Commit with no connection.
     *
     * @covers Lunr\Gravity\Database\SQLite3\SQLite3Connection::end_transaction
     */
    public function testEndTransactionThrowsExceptionWhenNotConnected()
    {
        $this->set_reflection_property_value('connected', FALSE);

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->will($this->returnValue(1));

        $this->sqlite3->expects($this->never())
                      ->method('exec');

        $this->expectException('Lunr\Gravity\Database\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->end_transaction();
    }

}
?>
