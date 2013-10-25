<?php

/**
 * This file contains the UserPermissionsTest class.
 *
 * PHP Version 5.4
 *
 * @category   Libraries
 * @package    Spark
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2013, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Spark\Facebook\Tests;

use Lunr\Spark\Facebook\User;
use Lunr\Halo\LunrBaseTest;
use ReflectionClass;

/**
 * This class contains the tests for the Facebook User class.
 *
 * @category   Libraries
 * @package    Spark
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @covers     Lunr\Spark\Facebook\User
 */
class UserPermissionsTest extends UserTest
{

    /**
     * Test that get_permissions() does not get permissions if the access token is null.
     *
     * @covers Lunr\Spark\Facebook\User::get_permissions
     */
    public function testGetPermissionsDoesNotGetPermissionsIfAccessTokenIsNull()
    {
        $this->cas->expects($this->once())
                  ->method('get')
                  ->with($this->equalTo('facebook'), $this->equalTo('access_token'))
                  ->will($this->returnValue(NULL));

        $method = $this->get_accessible_reflection_method('get_permissions');
        $method->invoke($this->class);

        $this->assertArrayEmpty($this->get_reflection_property_value('permissions'));
    }

    /**
     * Test that get_permissions() sets permissions on success.
     *
     * @covers Lunr\Spark\Facebook\User::get_permissions
     */
    public function testGetPermissionsSetsPermissionsOnSuccess()
    {
        $data = [
            'data' => [
                0 => [
                    'email' => 1,
                    'user_likes' => 1
                ]
            ]
        ];

        $this->cas->expects($this->exactly(2))
                  ->method('get')
                  ->with($this->equalTo('facebook'), $this->equalTo('access_token'))
                  ->will($this->returnValue('Token'));

        $this->curl->expects($this->once())
                   ->method('get_request')
                   ->with($this->equalTo('https://graph.facebook.com/me/permissions?access_token=Token'))
                   ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
                       ->method('__get')
                       ->with($this->equalTo('http_code'))
                       ->will($this->returnValue(200));

        $this->response->expects($this->once())
                       ->method('get_result')
                       ->will($this->returnValue(json_encode($data)));

        $method = $this->get_accessible_reflection_method('get_permissions');
        $method->invoke($this->class);

        $this->assertPropertySame('permissions', $data['data'][0]);
    }

    /**
     * Test that get_permissions() does not set permissions on error.
     *
     * @covers Lunr\Spark\Facebook\User::get_permissions
     */
    public function testGetPermissionsSetsPermissionsEmptyOnError()
    {
        $this->cas->expects($this->exactly(2))
                  ->method('get')
                  ->with($this->equalTo('facebook'), $this->equalTo('access_token'))
                  ->will($this->returnValue('Token'));

        $this->curl->expects($this->once())
                   ->method('get_request')
                   ->with($this->equalTo('https://graph.facebook.com/me/permissions?access_token=Token'))
                   ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
                       ->method('__get')
                       ->with($this->equalTo('http_code'))
                       ->will($this->returnValue(400));

        $method = $this->get_accessible_reflection_method('get_permissions');
        $method->invoke($this->class);

        $this->assertArrayEmpty($this->get_reflection_property_value('permissions'));
    }

    /**
     * Test that is_permissions_granted() returns TRUE when permissions are granted.
     *
     * @param string|array $permissions Set of permissions to check for.
     *
     * @dataProvider validPermissionsProvider
     * @covers       Lunr\Spark\Facebook\User::is_permission_granted
     */
    public function testIsPermissionGrantedReturnsTrueIfPermissionsGranted($permissions)
    {
        $granted = [ 'email' => 1, 'user_likes' => 1 ];

        $this->set_reflection_property_value('permissions', $granted);

        $method = $this->get_accessible_reflection_method('is_permission_granted');

        $this->assertTrue($method->invokeArgs($this->class, [ $permissions ]));
    }

    /**
     * Test that is_permissions_granted() returns FALSE when permissions are not granted.
     *
     * @param string|array $permissions Set of permissions to check for.
     *
     * @dataProvider invalidPermissionsProvider
     * @covers       Lunr\Spark\Facebook\User::is_permission_granted
     */
    public function testIsPermissionGrantedReturnsFalseIfPermissionsNotGranted($permissions)
    {
        $granted = [ 'email' => 1, 'user_likes' => 1 ];

        $this->set_reflection_property_value('permissions', $granted);

        $method = $this->get_accessible_reflection_method('is_permission_granted');

        $this->assertFalse($method->invokeArgs($this->class, [ $permissions ]));
    }

}

?>