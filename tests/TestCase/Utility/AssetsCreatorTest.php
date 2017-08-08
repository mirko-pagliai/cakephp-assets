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
namespace Assets\Test\TestCase\Utility;

use Assets\Utility\AssetsCreator;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\TestSuite\TestCase;
use Reflection\ReflectionTrait;

/**
 * AssetsCreatorTest class
 */
class AssetsCreatorTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Plugin::load('TestPlugin');

        Configure::write(ASSETS . '.target', TMP . 'assets');
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        Plugin::unload('TestPlugin');

        //Deletes all assets
        foreach (glob(Configure::read(ASSETS . '.target') . DS . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Test for `__construct()` method
     * @test
     */
    public function testConstruct()
    {
        $asset = new AssetsCreator('test', 'css');

        $this->assertInstanceOf(ASSETS . '\Utility\AssetsCreator', $asset);

        $this->assertEquals('css', $this->getProperty($asset, 'type'));

        $paths = $this->getProperty($asset, 'paths');
        $this->assertEquals(1, count($paths));
        $this->assertEquals(WWW_ROOT . 'css', dirname($paths[0]));
        $this->assertEquals('test.css', basename($paths[0]));

        $asset = $this->getProperty($asset, 'asset');
        $this->assertEquals(Configure::read(ASSETS . '.target'), dirname($asset));
        $this->assertRegExp('/^[0-9a-z]+\.css$/', basename($asset));
    }

    /**
     * Test for `__construct()` method, passing a no existing file
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage File `webroot/css/noExistingFile.css` doesn't exist
     * @test
     */
    public function testConstructNoExistingFile()
    {
        new AssetsCreator('noExistingFile', 'css');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage File `Plugin/TestPlugin/webroot/css/noExistingFile.css` doesn't exist
     * @test
     */
    public function testConstructNoExistingFileFromPlugin()
    {
        new AssetsCreator('TestPlugin.noExistingFile', 'css');
    }

    /**
     * Test for `__construct()` method, passing unsupported type
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Asset type `html` not supported
     * @test
     */
    public function testConstructUnsupportedType()
    {
        new AssetsCreator(null, 'html');
    }

    /**
     * Test for `resolvePath()` method
     * @ŧest
     */
    public function testResolvePath()
    {
        $expected = [WWW_ROOT . 'css' . DS . 'test.css'];

        $asset = new AssetsCreator('test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('test.css', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('/css/test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('/css/test.css', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('subdir/test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $expected = [WWW_ROOT . 'css' . DS . 'subdir' . DS . 'test.css'];
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('/othercssdir/test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $expected = [WWW_ROOT . 'othercssdir' . DS . 'test.css'];
        $this->assertEquals($expected, $result);

        //Tests array
        $asset = new AssetsCreator([
            'test',
            'subdir/test',
            '/othercssdir/test',
        ], 'css');
        $result = $this->getProperty($asset, 'paths');
        $expected = [
            WWW_ROOT . 'css' . DS . 'test.css',
            WWW_ROOT . 'css' . DS . 'subdir' . DS . 'test.css',
            WWW_ROOT . 'othercssdir' . DS . 'test.css',
        ];
        $this->assertEquals($expected, $result);

        //Tests plugins
        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css'];

        $asset = new AssetsCreator('TestPlugin.test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('TestPlugin.test.css', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('TestPlugin./css/test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $asset = new AssetsCreator('TestPlugin./css/test.css', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'subdir' . DS . 'test.css'];
        $asset = new AssetsCreator('TestPlugin.subdir/test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'othercssdir' . DS . 'test.css'];
        $asset = new AssetsCreator('TestPlugin./othercssdir/test', 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);

        //Tests array
        $expected = [
            Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css',
            Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'subdir' . DS . 'test.css',
            Plugin::path('TestPlugin') . 'webroot' . DS . 'othercssdir' . DS . 'test.css',
        ];
        $asset = new AssetsCreator([
            'TestPlugin.test',
            'TestPlugin.subdir/test',
            'TestPlugin./othercssdir/test'
        ], 'css');
        $result = $this->getProperty($asset, 'paths');
        $this->assertEquals($expected, $result);
    }

    /**
     * Test for `getAssetPath()` method
     * @test
     */
    public function testGetAssetPath()
    {
        $asset = (new AssetsCreator('test', 'css'));
        $result = $this->invokeMethod($asset, 'getAssetPath');
        $expected = Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', md5(serialize([
            [
                $file = WWW_ROOT . 'css' . DS . 'test.css',
                filemtime($file),
            ],
        ])), 'css');
        $this->assertEquals($expected, $result);

        //From plugin
        $asset = (new AssetsCreator('TestPlugin.test', 'css'));
        $result = $this->invokeMethod($asset, 'getAssetPath');
        $expected = Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', md5(serialize([
            [
                $file = Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css',
                filemtime($file),
            ],
        ])), 'css');
        $this->assertEquals($expected, $result);
    }

    /**
     * Test for `create()` method, using a css file
     * @test
     */
    public function testCreateWithCss()
    {
        $result = (new AssetsCreator('test', 'css'))->create();
        $this->assertRegExp('/^[a-z0-9]+$/', $result);

        $file = Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', $result, 'css');
        $this->assertFileExists($file);

        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}';
        $this->assertStringEqualsFile($file, $expected);

        //Tests array
        $result = (new AssetsCreator(['test', 'test2'], 'css'))->create();
        $this->assertRegExp('/^[a-z0-9]+$/', $result);

        $file = Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', $result, 'css');
        $this->assertFileExists($file);

        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}' .
            '#my-id2{font-size:16px}.my-class2{font-size:18px}';
        $this->assertStringEqualsFile($file, $expected);
    }

    /**
     * Test for `create()` method, using a js file
     * @test
     */
    public function testCreateWithJs()
    {
        $result = (new AssetsCreator('test', 'js'))->create();
        $this->assertRegExp('/^[a-z0-9]+$/', $result);

        $file = Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', $result, 'js');
        $this->assertFileExists($file);

        $expected = 'function other_alert()' . PHP_EOL .
            '{alert(\'Another alert\')}' . PHP_EOL .
            '$(function(){var msg=\'Ehi!\';alert(msg)})';
        $this->assertStringEqualsFile($file, $expected);

        //Tests array
        $result = (new AssetsCreator(['test', 'test2'], 'js'))->create();
        $this->assertRegExp('/^[a-z0-9]+$/', $result);

        $file = Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', $result, 'js');
        $this->assertFileExists($file);

        $expected = 'function other_alert()' . PHP_EOL .
            '{alert(\'Another alert\')}' . PHP_EOL .
            '$(function(){var msg=\'Ehi!\';alert(msg)});' .
            'var first=\'This is first\';' .
            'var second=\'This is second\';' .
            'alert(first+\' and \'+second)';
        $this->assertStringEqualsFile($file, $expected);
    }

    /**
     * Test for `create()` method. It tests the asset is created only if it
     *  does not exist
     * @test
     */
    public function testCreateReturnsExistingAsset()
    {
        //Creates the asset
        $result = (new AssetsCreator('test', 'css'))->create();

        //Sets the file path and the creation time
        $file = Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', $result, 'css');
        $time = filemtime($file);

        //Tries to create again the same asset. Now the creation time is the same
        $result = (new AssetsCreator('test', 'css'))->create();
        $this->assertEquals($time, filemtime($file));

        //Deletes asset and wait 1 second
        unlink($file);
        sleep(1);

        //Tries to create again the same asset. Now the creation time is different
        $result = (new AssetsCreator('test', 'css'))->create();
        $this->assertNotEquals($time, filemtime($file));
    }

    /**
     * Test for `create()` method with no existing target directory
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessageRegExp /^Failed to create file noExistingDir\/[a-z0-9]+\.css$/
     * @test
     */
    public function testCreateNoExistingTarget()
    {
        Configure::write(ASSETS . '.target', 'noExistingDir');

        (new AssetsCreator('test', 'css'))->create();
    }
}
