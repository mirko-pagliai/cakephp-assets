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
 */
namespace Assets\Test\TestCase\Controller;

use Assets\Utility\AssetsCreator;
use Cake\TestSuite\IntegrationTestCase;

/**
 * AssetControllerTest class
 */
class AssetsControllerTest extends IntegrationTestCase
{
    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->_compareBasePath = '';
        
        //Deletes all assets
        foreach (glob(ASSETS . DS . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Test for `asset()` method, with a css asset
     * @return void
     * @test
     */
    public function testAssetWithCss()
    {
        //This is the filename
        $filename = sprintf('%s.%s', AssetsCreator::css('test'), 'css');

        $this->get(sprintf('/assets/css/%s', $filename));
        
        $this->assertResponseOk();
        $this->assertContentType('text/css');
        $this->assertFileResponse(ASSETS . DS . $filename);
    }

    /**
     * Test for `asset()` method, with a js asset
     * @return void
     * @test
     */
    public function testAssetWithJs()
    {
        //This is the filename
        $filename = sprintf('%s.%s', AssetsCreator::script('test'), 'js');

        $this->get(sprintf('/assets/js/%s', $filename));

        $this->assertResponseOk();
        $this->assertContentType('application/javascript');
        $this->assertFileResponse(ASSETS . DS . $filename);
    }

    /**
     * Test for `asset()` method, with a a no existing file
     * @return void
     * @test
     */
    public function testAssetNoExistingFile()
    {
        $this->get('/assets/js/noexistingfile.js');
        
        $this->assertResponseError();
    }
}
