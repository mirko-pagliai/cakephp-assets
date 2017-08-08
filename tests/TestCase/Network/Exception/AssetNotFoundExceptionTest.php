<?php
/**
 * This file is part of Assets.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/assets
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Assets\Test\TestCase\Network\Exception;

use Assets\Network\Exception\AssetNotFoundException;
use Cake\TestSuite\TestCase;

/**
 * AssetNotFoundExceptionTest class
 */
class AssetNotFoundExceptionTest extends TestCase
{
    /**
     * Test for the exception
     * @expectedException Assets\Network\Exception\AssetNotFoundException
     * @expectedExceptionCode 404
     * @test
     * @throws AssetNotFoundException
     */
    public function testException()
    {
        throw new AssetNotFoundException;
    }

    /**
     * Test for the exception, with a message
     * @expectedException Assets\Network\Exception\AssetNotFoundException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Asset not found!
     * @test
     * @throws AssetNotFoundException
     */
    public function testExceptionWithMessage()
    {
        throw new AssetNotFoundException('Asset not found!');
    }
}
