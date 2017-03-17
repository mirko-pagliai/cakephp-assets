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

use Assets\Controller\AssetsController;
use Assets\Utility\AssetsCreator;
use Cake\Core\Configure;
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

        //Deletes all assets
        foreach (glob(Configure::read('Assets.target') . DS . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Test for `asset()` method, with a a no existing file
     * @expectedException Assets\Network\Exception\AssetNotFoundException
     * @expectedExceptionMessage File `/tmp/assets/noexistingfile.js` doesn't exist
     * @test
     */
    public function testAssetNoExistingFile()
    {
        (new AssetsController)->asset('noexistingfile.js');
    }

    /**
     * Test the response for `asset()` method, with a a no existing file
     * @test
     */
    public function testAssetNoExistingFileResponse()
    {
        $this->get('/assets/noexistingfile.js');
        $this->assertEquals(404, $this->_response->getStatusCode());
        $this->assertNull($this->_response->getFile());
        $this->assertResponseError();
    }

    /**
     * Test for `asset()` method, with a css asset
     * @test
     */
    public function testAssetWithCss()
    {
        //This is the filename
        $filename = sprintf('%s.%s', (new AssetsCreator('test', 'css'))->create(), 'css');

        $this->get(sprintf('/assets/%s', $filename));
        $this->assertResponseOk();
        $this->assertContentType('text/css');
        $this->assertFileResponse(Configure::read('Assets.target') . DS . $filename);

        $file = $this->_response->getFile();

        $this->assertInstanceOf('Cake\Filesystem\File', $file);
        $this->assertEquals([
            'dirname' => Configure::read('Assets.target'),
            'basename' => $filename,
            'extension' => 'css',
            'filename' => pathinfo($filename, PATHINFO_FILENAME),
            'filesize' => filesize(Configure::read('Assets.target') . DS . $filename),
            'mime' => 'text/plain',
        ], $file->info);
    }

    /**
     * Test for `asset()` method, with a js asset
     * @test
     */
    public function testAssetWithJs()
    {
        //This is the filename
        $filename = sprintf('%s.%s', (new AssetsCreator('test', 'js'))->create(), 'js');

        $this->get(sprintf('/assets/%s', $filename));
        $this->assertResponseOk();
        $this->assertContentType('application/javascript');
        $this->assertFileResponse(Configure::read('Assets.target') . DS . $filename);

        $file = $this->_response->getFile();

        $this->assertInstanceOf('Cake\Filesystem\File', $file);
        $this->assertEquals([
            'dirname' => Configure::read('Assets.target'),
            'basename' => $filename,
            'extension' => 'js',
            'filename' => pathinfo($filename, PATHINFO_FILENAME),
            'filesize' => filesize(Configure::read('Assets.target') . DS . $filename),
            'mime' => 'text/plain',
        ], $file->info);
    }
}
