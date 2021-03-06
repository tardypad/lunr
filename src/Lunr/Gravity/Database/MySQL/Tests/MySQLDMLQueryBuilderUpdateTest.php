<?php

/**
 * This file contains the MySQLDMLQueryBuilderUpdateTest class.
 *
 * @package    Lunr\Gravity\Database\MySQL
 * @author     Felipe Martinez <felipe@m2mobi.com>
 * @copyright  2012-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database\MySQL\Tests;

use Lunr\Gravity\Database\MySQL\MySQLDMLQueryBuilder;

/**
 * This class contains the tests for the query parts necessary to build
 * update queries.
 *
 * @covers Lunr\Gravity\Database\MySQL\MySQLDMLQueryBuilder
 */
class MySQLDMLQueryBuilderUpdateTest extends MySQLDMLQueryBuilderTest
{

    /**
     * Test that standard update modes are handled correctly.
     *
     * @param string $mode Valid update modes.
     *
     * @dataProvider updateModesStandardProvider
     * @covers       Lunr\Gravity\Database\MySQL\MySQLDMLQueryBuilder::update_mode
     */
    public function testUpdateModeSetsStandardCorrectly($mode)
    {
        $property = $this->builder_reflection->getProperty('update_mode');
        $property->setAccessible(TRUE);

        $this->builder->update_mode($mode);

        $this->assertContains($mode, $property->getValue($this->builder));
    }

    /**
     * Test that unknown select modes are ignored.
     *
     * @depends Lunr\Gravity\Database\Tests\DatabaseDMLQueryBuilderBaseTest::testUpdateModeEmptyByDefault
     * @covers  Lunr\Gravity\Database\MySQL\MySQLDMLQueryBuilder::update_mode
     */
    public function testUpdateModeIgnoresUnknownValues()
    {
        $property = $this->builder_reflection->getProperty('update_mode');
        $property->setAccessible(TRUE);

        $this->builder->update_mode('UNSUPPORTED');

        $value = $property->getValue($this->builder);

        $this->assertInternalType('array', $value);
        $this->assertEmpty($value);
    }

}

?>
