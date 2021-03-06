<?php

/**
 * Abstract query escaper class.
 *
 * @package    Lunr\Gravity\Database
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2012-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database;

/**
 * This class provides common escaping methods for SQL query parts.
 */
abstract class DatabaseQueryEscaper implements QueryEscaperInterface
{

    /**
     * Instance of the Database Connection.
     * @var DatabaseConnection
     */
    protected $db;

    /**
     * The left identifier delimiter.
     * @var String
     */
    const IDENTIFIER_DELIMITER_L = '`';

    /**
     * The right identifier delimiter.
     * @var String
     */
    const IDENTIFIER_DELIMITER_R = '`';

    /**
     * Constructor.
     *
     * @param DatabaseConnection $db Instance of the DatabaseConnection class.
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->db);
    }

    /**
     * Allow value escapers to handle NULL values more gracefully.
     *
     * @param string $name      Method name that was called
     * @param array  $arguments Arguments passed to the method
     *
     * @return mixed $return Escaped value or NULL
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 8) == 'null_or_' && strpos($name, 'value'))
        {
            if ($arguments[0] === NULL)
            {
                return NULL;
            }
            else
            {
                $method = substr($name, 8);

                return $this->{$method}(...$arguments);
            }
        }
    }

    /**
     * Define and escape input as column.
     *
     * @param string $name      Input
     * @param string $collation Collation name
     *
     * @return string $return Defined and escaped column name
     */
    public function column($name, $collation = '')
    {
        return trim($this->collate($this->escape_location_reference($name), $collation));
    }

    /**
     * Define and escape input as a result column.
     *
     * @param string $column Result column name
     * @param string $alias  Alias
     *
     * @return string $return Defined and escaped result column
     */
    public function result_column($column, $alias = '')
    {
        $column = $this->escape_location_reference($column);

        if ($alias === '' || $column === '*')
        {
            return $column;
        }
        else
        {
            return $column . ' AS ' . static::IDENTIFIER_DELIMITER_L . $alias . static::IDENTIFIER_DELIMITER_R;
        }
    }

    /**
     * Define and escape input as a result column and transform values to hexadecimal.
     *
     * @param string $column Result column name
     * @param string $alias  Alias
     *
     * @return string $return Defined and escaped result column
     */
    public function hex_result_column($column, $alias = '')
    {
        $alias = ($alias === '') ? $column : $alias;
        $alias = static::IDENTIFIER_DELIMITER_L . $alias . static::IDENTIFIER_DELIMITER_R;

        return 'HEX(' . $this->escape_location_reference($column) . ') AS ' . $alias;
    }

    /**
     * Define and escape input as table.
     *
     * @param string $table Table name
     * @param string $alias Alias
     *
     * @return string $return Defined and escaped table
     */
    public function table($table, $alias = '')
    {
        $table = $this->escape_location_reference($table);

        if ($alias === '')
        {
            return $table;
        }
        else
        {
            return $table . ' AS ' . static::IDENTIFIER_DELIMITER_L . $alias . static::IDENTIFIER_DELIMITER_R;
        }
    }

    /**
     * Define and escape input as integer value.
     *
     * @param mixed $value Input to escape as integer
     *
     * @return integer Defined and escaped Integer value
     */
    public function intvalue($value)
    {
        return intval($value);
    }

    /**
     * Define and escape input as floating point value.
     *
     * @param mixed $value Input to escape as float
     *
     * @return integer Defined and escaped float value
     */
    public function floatvalue($value)
    {
        return floatval($value);
    }

    /**
    * Define input as a query within parentheses.
    *
    * @param string $value Input
    *
    * @return string $return Defined within parentheses
    */
    public function query_value($value)
    {
        return empty($value) ? '' : '(' . $value . ')';
    }

    /**
     * Define input as a csv from an array within parentheses.
     *
     * @param array $array_values Input
     *
     * @return string $output Defined, escaped and within parentheses
     */
    public function list_value($array_values)
    {
        if (is_array($array_values) === FALSE)
        {
            return '';
        }

        return '(' . implode(',', $array_values) . ')';
    }

    /**
     * Define a special collation.
     *
     * @param mixed  $value     Input
     * @param string $collation Collation name
     *
     * @return string $return Value with collation definition.
     */
    protected function collate($value, $collation)
    {
        if ($collation == '')
        {
            return $value;
        }
        else
        {
            return $value . ' COLLATE ' . $collation;
        }
    }

    /**
     * Escape a location reference (database, table, column).
     *
     * @param string $col Column
     *
     * @return string escaped column list
     */
    protected function escape_location_reference($col)
    {
        $parts = explode('.', $col);
        $col   = '';
        foreach ($parts as $part)
        {
            $part = trim($part);
            if ($part == '*')
            {
                $col .= $part;
            }
            else
            {
                $col .= static::IDENTIFIER_DELIMITER_L . $part . static::IDENTIFIER_DELIMITER_L . '.';
            }
        }

        return trim($col, '.');
    }

}

?>
