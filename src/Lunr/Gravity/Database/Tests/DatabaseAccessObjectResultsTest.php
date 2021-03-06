<?php

/**
 * This file contains the DatabaseAccessObjectResultsTest class.
 *
 * @package    Lunr\Gravity\Database
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2012-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database\Tests;

use Lunr\Gravity\Database\DatabaseAccessObject;

/**
 * This class contains the tests for the DatabaseAccessObject class.
 *
 * Tests for returning result information.
 *
 * @covers Lunr\Gravity\Database\DatabaseAccessObject
 */
class DatabaseAccessObjectResultsTest extends DatabaseAccessObjectTest
{

    /**
     * Testcase constructor.
     */
    public function setUp()
    {
        $this->setUpNoPool();
    }

    /**
     * Test that indexed_result_array() throws an exception if query failed.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::indexed_result_array
     */
    public function testIndexedResultArrayThrowsExceptionIfQueryFailed()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(TRUE));

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->will($this->returnValue('message'));

        $query->expects($this->exactly(2))
              ->method('query')
              ->will($this->returnValue('query'));

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException('Lunr\Gravity\Database\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');

        $method = $this->get_accessible_reflection_method('indexed_result_array');

        $method->invokeArgs($this->class, [ &$query, 'key2' ]);
    }

    /**
     * Test that indexed_result_array() returns an empty array if query has no results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::indexed_result_array
     */
    public function testIndexedResultArrayReturnsEmptyArrayIfQueryHasNoResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(0));

        $method = $this->get_accessible_reflection_method('indexed_result_array');

        $result = $method->invokeArgs($this->class, [ &$query, 'key2' ]);

        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }

    /**
     * Test that indexed_result_array() returns an array of values if query has results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::indexed_result_array
     */
    public function testIndexedResultArrayReturnsArrayIfQueryHasResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(1));

        $query_result = [
            [
                'key'  => 'value',
                'key2' => 'index',
            ],
            [
                'key'  => 'value2',
                'key2' => 'index2',
            ],
        ];

        $query->expects($this->once())
              ->method('result_array')
              ->will($this->returnValue($query_result));

        $method = $this->get_accessible_reflection_method('indexed_result_array');

        $result = $method->invokeArgs($this->class, [ &$query, 'key2' ]);

        $indexed_result = [
            'index'  => [
                'key'  => 'value',
                'key2' => 'index',
            ],
            'index2' => [
                'key'  => 'value2',
                'key2' => 'index2',
            ],
        ];

        $this->assertInternalType('array', $result);
        $this->assertEquals($indexed_result, $result);
    }

    /**
     * Test that result_array() throws an exception if query failed.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_array
     */
    public function testResultArrayThrowsExceptionIfQueryFailed()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(TRUE));

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->will($this->returnValue('message'));

        $query->expects($this->exactly(2))
              ->method('query')
              ->will($this->returnValue('query'));

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException('Lunr\Gravity\Database\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');

        $method = $this->get_accessible_reflection_method('result_array');

        $method->invokeArgs($this->class, [ &$query ]);
    }

    /**
     * Test that result_array() returns an empty array if query has no results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_array
     */
    public function testResultArrayReturnsEmptyArrayIfQueryHasNoResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(0));

        $method = $this->get_accessible_reflection_method('result_array');

        $result = $method->invokeArgs($this->class, [ &$query ]);

        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }

    /**
     * Test that result_array() returns an array of values if query has results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_array
     */
    public function testResultArrayReturnsArrayIfQueryHasResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(1));

        $query_result = [ 0 => [ 'key' => 'value' ] ];

        $query->expects($this->once())
              ->method('result_array')
              ->will($this->returnValue($query_result));

        $method = $this->get_accessible_reflection_method('result_array');

        $result = $method->invokeArgs($this->class, [ &$query ]);

        $this->assertInternalType('array', $result);
        $this->assertEquals($query_result, $result);
    }

    /**
     * Test that result_row() throws an exception if query failed.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_row
     */
    public function testResultRowThrowsExceptionIfQueryFailed()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(TRUE));

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->will($this->returnValue('message'));

        $query->expects($this->exactly(2))
              ->method('query')
              ->will($this->returnValue('query'));

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException('Lunr\Gravity\Database\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');

        $method = $this->get_accessible_reflection_method('result_row');

        $method->invokeArgs($this->class, [ &$query ]);
    }

    /**
     * Test that result_row() returns an empty array if query has no results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_row
     */
    public function testResultRowReturnsEmptyArrayIfQueryHasNoResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(0));

        $method = $this->get_accessible_reflection_method('result_row');

        $result = $method->invokeArgs($this->class, [ &$query ]);

        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }

    /**
     * Test that result_row() returns an array of values if query has results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_row
     */
    public function testResultRowReturnsArrayIfQueryHasResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(1));

        $query_result = [ 'key' => 'value' ];

        $query->expects($this->once())
              ->method('result_row')
              ->will($this->returnValue($query_result));

        $method = $this->get_accessible_reflection_method('result_row');

        $result = $method->invokeArgs($this->class, [ &$query ]);

        $this->assertInternalType('array', $result);
        $this->assertEquals($query_result, $result);
    }

    /**
     * Test that result_column() throws an exception if query failed.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_column
     */
    public function testResultColumnThrowsExceptionIfQueryFailed()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(TRUE));

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->will($this->returnValue('message'));

        $query->expects($this->exactly(2))
              ->method('query')
              ->will($this->returnValue('query'));

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException('Lunr\Gravity\Database\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');

        $method = $this->get_accessible_reflection_method('result_column');

        $method->invokeArgs($this->class, [ &$query, 'col' ]);
    }

    /**
     * Test that result_column() returns an empty array if query has no results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_column
     */
    public function testResultColumnReturnsEmptyArrayIfQueryHasNoResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(0));

        $method = $this->get_accessible_reflection_method('result_column');

        $result = $method->invokeArgs($this->class, [ &$query, 'col' ]);

        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }

    /**
     * Test that result_column() returns an array of values if query has results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_column
     */
    public function testResultColumnReturnsArrayIfQueryHasResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(1));

        $query_result = [ 0 => 'value' ];

        $query->expects($this->once())
              ->method('result_column')
              ->will($this->returnValue($query_result));

        $method = $this->get_accessible_reflection_method('result_column');

        $result = $method->invokeArgs($this->class, [ &$query, 'col' ]);

        $this->assertInternalType('array', $result);
        $this->assertEquals($query_result, $result);
    }

    /**
     * Test that result_cell() throws an exception if query failed.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_cell
     */
    public function testResultCellThrowsExceptionIfQueryFailed()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(TRUE));

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->will($this->returnValue('message'));

        $query->expects($this->exactly(2))
              ->method('query')
              ->will($this->returnValue('query'));

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException('Lunr\Gravity\Database\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');

        $method = $this->get_accessible_reflection_method('result_cell');

        $method->invokeArgs($this->class, [ &$query, 'col' ]);
    }

    /**
     * Test that result_cell() returns an empty string if query has no results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_cell
     */
    public function testResultCellReturnsEmptyStringIfQueryHasNoResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(0));

        $method = $this->get_accessible_reflection_method('result_cell');

        $result = $method->invokeArgs($this->class, [ &$query, 'col' ]);

        $this->assertEquals('', $result);
    }

    /**
     * Test that result_cell() returns a value if query has results.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_cell
     */
    public function testResultCellReturnsValueIfQueryHasResults()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('number_of_rows')
              ->will($this->returnValue(1));

        $query_result = 'value';

        $query->expects($this->once())
              ->method('result_cell')
              ->will($this->returnValue($query_result));

        $method = $this->get_accessible_reflection_method('result_cell');

        $result = $method->invokeArgs($this->class, [ &$query, 'col' ]);

        $this->assertEquals($query_result, $result);
    }

    /**
     * Test that result_retry() returns the same result query if there is no deadlock.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_retry
     */
    public function testResultRetryReturnsQueryInNoDeadlock()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_deadlock')
              ->will($this->returnValue(FALSE));

        $method = $this->get_accessible_reflection_method('result_retry');

        $result = $method->invokeArgs($this->class, [ &$query ]);

        $this->assertSame($result, $query);
    }

    /**
     * Test that result_retry() re-executes the query if there is a deadlock.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_retry
     */
    public function testResultRetryReExecutesQueryInDeadlock()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->at(0))
              ->method('has_deadlock')
              ->will($this->returnValue(TRUE));

        $query->expects($this->at(1))
              ->method('query')
              ->will($this->returnValue('sql_query'));

        $this->db->expects($this->once())
                 ->method('query')
                 ->with('sql_query')
                 ->will($this->returnValue($query));

        $method = $this->get_accessible_reflection_method('result_retry');

        $result = $method->invokeArgs($this->class, [ &$query, 1 ]);

        $this->assertSame($result, $query);
    }

    /**
     * Test that result_retry() re-executes the query if there is a deadlock more than once.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_retry
     */
    public function testResultRetryReExecutesQueryInDeadlockMoreThanOnce()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->at(0))
              ->method('has_deadlock')
              ->will($this->returnValue(TRUE));

        $query->expects($this->at(1))
              ->method('query')
              ->will($this->returnValue('sql_query'));

        $this->db->expects($this->once())
                 ->method('query')
                 ->with('sql_query')
                 ->will($this->returnValue($query));

        $query->expects($this->at(2))
              ->method('has_deadlock')
              ->will($this->returnValue(FALSE));

        $method = $this->get_accessible_reflection_method('result_retry');

        $result = $method->invokeArgs($this->class, [ &$query, 2 ]);

        $this->assertSame($result, $query);
    }

    /**
     * Test that result_boolean() throws an exception on a failure.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_boolean
     */
    public function testResultHasFailedTrueIfQueryFailed()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
            ->disableOriginalConstructor()
            ->getMock();

        $query->expects($this->once())
            ->method('has_failed')
            ->will($this->returnValue(TRUE));

        $query->expects($this->exactly(2))
            ->method('error_message')
            ->will($this->returnValue('message'));

        $query->expects($this->exactly(2))
            ->method('query')
            ->will($this->returnValue('query'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException('Lunr\Gravity\Database\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');

        $method = $this->get_accessible_reflection_method('result_boolean');

        $method->invokeArgs($this->class, [ &$query ]);
    }

    /**
     * Test that result_retry() returns TRUE on succesfull execution.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::result_boolean
     */
    public function testResultHasFailedFalseIfQuerySuccesfull()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
            ->disableOriginalConstructor()
            ->getMock();

        $query->expects($this->once())
            ->method('has_failed')
            ->will($this->returnValue(FALSE));

        $method = $this->get_accessible_reflection_method('result_boolean');

        $result = $method->invokeArgs($this->class, [ &$query ]);

        $this->assertTrue($result);
    }

    /**
     * Test that get_affected_rows() throws an exception on a failure.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::get_affected_rows
     */
    public function testGetAffectedRowsThrowsExceptionIfQueryFailed()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(TRUE));

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->will($this->returnValue('message'));

        $query->expects($this->exactly(2))
              ->method('query')
              ->will($this->returnValue('query'));

        $this->logger->expects($this->once())
             ->method('error')
             ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException('Lunr\Gravity\Database\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');

        $method = $this->get_accessible_reflection_method('get_affected_rows');

        $method->invokeArgs($this->class, [ &$query ]);
    }

    /**
     * Test that get_affected_rows() returns affected rows on succesfull execution.
     *
     * @covers Lunr\Gravity\Database\DatabaseAccessObject::get_affected_rows
     */
    public function testGetAffectedRowsReturnsAffectedRowsOnSuccess()
    {
        $query = $this->getMockBuilder('Lunr\Gravity\Database\MySQL\MySQLQueryResult')
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->will($this->returnValue(FALSE));

        $query->expects($this->once())
              ->method('affected_rows')
              ->will($this->returnValue(100));

        $method = $this->get_accessible_reflection_method('get_affected_rows');

        $result = $method->invokeArgs($this->class, [ &$query ]);

        $this->assertSame(100, $result);
    }

}

?>
