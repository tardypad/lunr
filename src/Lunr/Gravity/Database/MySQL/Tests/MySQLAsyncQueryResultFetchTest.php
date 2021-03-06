<?php

/**
 * This file contains the MySQLAsyncQueryResultFetchTest class.
 *
 * @package    Lunr\Gravity\Database\MySQL
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2012-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database\MySQL\Tests;

use Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult;
use MySQLi_Result;

/**
 * This class contains tests for the fetching of data in the MySQLAsyncQueryResult class.
 *
 * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult
 */
class MySQLAsyncQueryResultFetchTest extends MySQLAsyncQueryResultTest
{

    /**
     * Test that has_failed() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::has_failed
     */
    public function testHasFailedFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->has_failed();
    }

    /**
     * Test that has_failed() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::has_failed
     */
    public function testHasFailedDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->has_failed();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that affected_rows() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::affected_rows
     */
    public function testAffectedRowsFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->affected_rows();
    }

    /**
     * Test that affected_rows() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::affected_rows
     */
    public function testAffectedRowsDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->affected_rows();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that number_of_rows() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::number_of_rows
     */
    public function testNumberOfRowsRowsFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->number_of_rows();
    }

    /**
     * Test that number_of_rows() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::number_of_rows
     */
    public function testNumberOfRowsDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->number_of_rows();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that error_message() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorMessageFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->error_message();
    }

    /**
     * Test that error_message() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorMessageDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->error_message();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that error_number() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorNumberFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->error_number();
    }

    /**
     * Test that error_number() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorNumberDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->error_number();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that insert_id() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testInsertIDFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->insert_id();
    }

    /**
     * Test that insert_id() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testInsertIDDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->insert_id();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that result_array() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_array
     */
    public function testResultArrayFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_array();
    }

    /**
     * Test that result_array() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_array
     */
    public function testResultArrayDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_array();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that result_row() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_row
     */
    public function testResultRowFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_row();
    }

    /**
     * Test that result_row() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_row
     */
    public function testResultRowDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_row();

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that result_column() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_column
     */
    public function testResultColumnFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_column('col');
    }

    /**
     * Test that result_column() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_column
     */
    public function testResultColumnDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_column('col');

        $property->setValue($this->result, FALSE);
    }

    /**
     * Test that result_cell() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_cell
     */
    public function testResultCellFetchesDataIfFetchedIsFalse()
    {
        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_cell('cell');
    }

    /**
     * Test that result_cell() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\Database\MySQL\MySQLAsyncQueryResult::result_cell
     */
    public function testResultCellDoesNotFetchDataIfFetchedIsTrue()
    {
        $property = $this->result_reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->result, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->result->result_cell('cell');

        $property->setValue($this->result, FALSE);
    }

}

?>
