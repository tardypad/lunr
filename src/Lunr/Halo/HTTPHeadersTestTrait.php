<?php

/**
 * This file contains the HTTPHeadersTestTrait.
 *
 * PHP Version 5.4
 *
 * @category   Tests
 * @package    Halo
 * @subpackage Libraries
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 * @copyright  2014, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Halo;

/**
 * This trait contains test methods to verify HTTP headers
 *
 * @category   Tests
 * @package    Halo
 * @subpackage Libraries
 * @author     Damien Tardy-Panis <damien@m2mobi.com>

 */
trait HTTPHeadersTestTrait
{

    /**
     * Returns the prefix used to test headers.
     *
     * @return String
     */
    protected static function header_test_prefix()
    {
        return 'X-Lunr-Test: ';
    }

    /**
     * Initialize the environment to be able to test the headers.
     *
     * @return void
     */
    protected function start_headers_tests()
    {
        runkit_function_copy('header', 'header_copy_lunr');
        runkit_function_redefine('header', '$string', 'header_copy_lunr("' . self::header_test_prefix() . '$string", FALSE);');

        runkit_function_copy('http_response_code', 'http_response_code_backup_lunr');
        runkit_function_redefine('http_response_code', '$code', 'header_copy_lunr("' . self::header_test_prefix() . 'HTTP/1.1 $code", FALSE);');
    }

    /**
     * Restore the environment after the headers testing.
     *
     * @return void
     */
    protected function finish_headers_tests()
    {
        runkit_function_remove('header');
        runkit_function_rename('header_copy_lunr', 'header');

        runkit_function_remove('http_response_code');
        runkit_function_rename('http_response_code_backup_lunr', 'http_response_code');
    }

    /**
     * Assert that a specific header is part of the HTTP headers returned.
     *
     * @param String $header header to check
     *
     * @return void
     */
    public function assertHeadersContains($header)
    {
        $headers = xdebug_get_headers();

        $this->assertContains(self::header_test_prefix() . $header, $headers);
    }

    /**
     * Assert the return code of HTTP.
     *
     * @param Integer $code Return code expected
     *
     * @return void
     */
    public function assertReturnCode($code)
    {
        $headers = xdebug_get_headers();

        $header_code = self::header_test_prefix() . "HTTP/1.1 $code";

        $has_correct_return_code = FALSE;

        foreach ($headers as $header)
        {
            if (strpos($header, $header_code) === 0)
            {
                $has_correct_return_code = TRUE;
                break;
            }
        }

        $this->assertTrue($has_correct_return_code);
    }

}

?>
