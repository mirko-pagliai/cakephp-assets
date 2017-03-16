<?php
/**
 * This file is part of Assets.
 *
 * Assets is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * Assets is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Assets.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 * @see         https://github.com/matthiasmullie/minify
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
