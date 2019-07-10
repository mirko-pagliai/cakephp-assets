<?php
/**
 * This file is part of Assets.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/cakephp-assets
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Assets\Test\TestCase\Controller;

use Assets\Controller\AssetsController;
use Assets\Http\Exception\AssetNotFoundException;
use Assets\TestSuite\IntegrationTestCase;
use Assets\Utility\AssetsCreator;
use Cake\Core\Configure;

/**
 * AssetControllerTest class
 */
class AssetsControllerTest extends IntegrationTestCase
{
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
        $this->assertEquals([
            'dirname' => Configure::read('Assets.target'),
            'basename' => $filename,
            'extension' => 'css',
            'filename' => pathinfo($filename, PATHINFO_FILENAME),
            'filesize' => filesize(Configure::read('Assets.target') . DS . $filename),
            'mime' => 'text/plain',
        ], $this->_response->getFile()->info);

        //Gets the `Last-Modified` header
        $lastModified = $this->_response->header()['Last-Modified'];
        $this->assertNotEmpty($lastModified);

        //It still requires the same asset file. It gets the 304 status code
        sleep(1);
        $this->configRequest(['headers' => ['If-Modified-Since' => $lastModified]]);
        $this->get(sprintf('/assets/%s', $filename));
        $this->assertResponseCode(304);

        if (IS_WIN) {
            $this->markTestSkipped();
        }

        //Deletes the asset file. Now the `Last-Modified` header is different
        @unlink(Configure::read('Assets.target') . DS . $filename);

        sleep(1);
        $filename = sprintf('%s.%s', (new AssetsCreator('test', 'css'))->create(), 'css');
        $this->get(sprintf('/assets/%s', $filename));
        $this->assertResponseOk();
        $this->assertNotEquals($lastModified, $this->_response->header()['Last-Modified']);

        //With a a no existing file
        $this->expectException(AssetNotFoundException::class);
        $this->expectExceptionMessage('File `' . Configure::read('Assets.target') . DS . 'noexistingfile.css` doesn\'t exist');
        $this->disableErrorHandlerMiddleware();
        $this->get('/assets/noexistingfile.css');
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
        $this->assertEquals([
            'dirname' => Configure::read('Assets.target'),
            'basename' => $filename,
            'extension' => 'js',
            'filename' => pathinfo($filename, PATHINFO_FILENAME),
            'filesize' => filesize(Configure::read('Assets.target') . DS . $filename),
            'mime' => 'text/plain',
        ], $this->_response->getFile()->info);
    }
}
