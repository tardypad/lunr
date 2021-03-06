<?php

/**
 * This file contains the DatabaseDMLQueryBuilderGetSelectQueryTest class.
 *
 * @package    Lunr\Gravity\Database
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @author     Olivier Wizen <olivier@m2mobi.com>
 * @author     Felipe Martinez <felipe@m2mobi.com>
 * @copyright  2012-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database\Tests;

/**
 * This class contains the tests for the setup and the final query creation.
 *
 * @covers Lunr\Gravity\Database\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderGetSelectQueryTest extends DatabaseDMLQueryBuilderTest
{

    /**
     * Test getting a select query.
     *
     * @depends Lunr\Gravity\Database\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithEmptySelectComponent
     * @depends Lunr\Gravity\Database\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateSelectModes
     * @covers  Lunr\Gravity\Database\DatabaseDMLQueryBuilder::get_select_query
     */
    public function testGetSelectQuery()
    {
        $this->set_reflection_property_value('from', 'FROM table');
        $this->set_reflection_property_value('select_mode', [ 'DISTINCT', 'SQL_CACHE' ]);
        $this->set_reflection_property_value('select', 'col');
        $this->set_reflection_property_value('join', 'INNER JOIN table1');
        $this->set_reflection_property_value('where', 'WHERE a = b');
        $this->set_reflection_property_value('order_by', 'ORDER BY col ASC');
        $this->set_reflection_property_value('group_by', 'GROUP BY col');
        $this->set_reflection_property_value('having', 'HAVING a = b');
        $this->set_reflection_property_value('limit', 'LIMIT 1');
        $this->set_reflection_property_value('lock_mode', 'FOR UPDATE');

        $string  = 'SELECT DISTINCT SQL_CACHE col FROM table INNER JOIN table1 WHERE a = b ';
        $string .= 'GROUP BY col HAVING a = b ORDER BY col ASC LIMIT 1 FOR UPDATE';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
     * Test getting a select query with undefined from clause.
     *
     * @depends Lunr\Gravity\Database\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithEmptySelectComponent
     * @depends Lunr\Gravity\Database\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateSelectModes
     * @covers  Lunr\Gravity\Database\DatabaseDMLQueryBuilder::get_select_query
     */
    public function testGetSelectQueryWithUndefinedFromClause()
    {
        $this->set_reflection_property_value('select_mode', [ 'DISTINCT', 'SQL_CACHE' ]);
        $this->set_reflection_property_value('select', 'col');

        $string = 'SELECT DISTINCT SQL_CACHE col';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
    * Test getting a select query when the compound property (UNION, INTERSECT or EXCEPT) is set.
    *
    * @depends Lunr\Gravity\Database\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithEmptySelectComponent
    * @depends Lunr\Gravity\Database\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateSelectModes
    * @covers  Lunr\Gravity\Database\DatabaseDMLQueryBuilder::get_select_query
    */
    public function testGetSelectQueryWithCompoundConnector()
    {
        $this->set_reflection_property_value('from', 'FROM table');
        $this->set_reflection_property_value('select', 'col');
        $this->set_reflection_property_value('compound', 'UNION (SELECT col2 FROM table2)');

        $string = '(SELECT col FROM table) UNION (SELECT col2 FROM table2)';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
     * Test getting a select query using with statement
     */
    public function testGetSelectQueryUsingWith()
    {

        $this->set_reflection_property_value('with', 'alias AS ( query )');
        $this->set_reflection_property_value('select', '*');
        $this->set_reflection_property_value('from', 'FROM alias');

        $string = 'WITH alias AS ( query ) SELECT * FROM alias';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
     * Test getting a select query using a recursive with statement
     */
    public function testGetSelectQueryUsingRecursiveWith()
    {
        $this->set_reflection_property_value('with', 'alias AS ( query )');
        $this->set_reflection_property_value('is_recursive', TRUE);
        $this->set_reflection_property_value('select', '*');
        $this->set_reflection_property_value('from', 'FROM alias');

        $string = 'WITH RECURSIVE alias AS ( query ) SELECT * FROM alias';

        $this->assertEquals($string, $this->class->get_select_query());
    }

}

?>
