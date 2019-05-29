<?php
/**
 * This file is part of cakephp-assets.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/cakephp-assets
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Assets\Test\TestCase\Utility;

use Assets\TestSuite\TestCase;
use Assets\Utility\AssetsCreator;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\TestSuite\StringCompareTrait;
use ErrorException;
use InvalidArgumentException;

/**
 * AssetsCreatorTest class
 */
class AssetsCreatorTest extends TestCase
{
    use StringCompareTrait;

    /**
     * Test for `__construct()` method
     * @test
     */
    public function testConstruct()
    {
        $asset = new AssetsCreator('test', 'css');
        $this->assertEquals('css', $this->getProperty($asset, 'type'));

        $paths = $this->getProperty($asset, 'paths');
        $this->assertCount(1, $paths);
        $this->assertEquals(WWW_ROOT . 'css', dirname($paths[0]));
        $this->assertEquals('test.css', basename($paths[0]));

        $asset = $this->getProperty($asset, 'asset');
        $this->assertEquals(Configure::read('Assets.target'), dirname($asset));
        $this->assertRegExp('/^[\d\w]+\.css$/', basename($asset));

        //With unsupported type
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Asset type `html` not supported');
        new AssetsCreator(null, 'html');
    }

    /**
     * Test for `resolveAssetPath()` method
     * @test
     */
    public function testResolveAssetPath()
    {
        $resolveAssetPathMethod = function (AssetsCreator $assetCreatorInstance) {
            return $this->invokeMethod($assetCreatorInstance, 'resolveAssetPath');
        };

        $expected = Configure::read('Assets.target') . DS . sprintf('%s.%s', md5(serialize([[
            $file = WWW_ROOT . 'css' . DS . 'test.css',
            filemtime($file),
        ]])), 'css');
        $this->assertEquals($expected, $resolveAssetPathMethod((new AssetsCreator('test', 'css'))));

        //From plugin
        $this->loadPlugins(['TestPlugin']);
        $expected = Configure::read('Assets.target') . DS . sprintf('%s.%s', md5(serialize([[
            $file = Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css',
            filemtime($file),
        ]])), 'css');
        $this->assertEquals($expected, $resolveAssetPathMethod((new AssetsCreator('TestPlugin.test', 'css'))));
    }

    /**
     * Test for `resolveFilePaths()` method
     * @ŧest
     */
    public function testResolveFilePaths()
    {
        $pathsProperty = function (AssetsCreator $assetCreatorInstance) {
            return $this->getProperty($assetCreatorInstance, 'paths');
        };

        $expected = [WWW_ROOT . 'css' . DS . 'test.css'];
        foreach ([
            'test',
            'test.css',
            '/css/test',
            '/css/test.css',
        ] as $path) {
            $this->assertEquals($expected, $pathsProperty(new AssetsCreator($path, 'css')));
        }
        foreach ([
            'subdir/test' => [WWW_ROOT . 'css' . DS . 'subdir' . DS . 'test.css'],
            '/othercssdir/test' => [WWW_ROOT . 'othercssdir' . DS . 'test.css'],
        ] as $path => $expected) {
            $this->assertEquals($expected, $pathsProperty(new AssetsCreator($path, 'css')));
        }

        //Tests array
        $expected = [
            WWW_ROOT . 'css' . DS . 'test.css',
            WWW_ROOT . 'css' . DS . 'subdir' . DS . 'test.css',
            WWW_ROOT . 'othercssdir' . DS . 'test.css',
        ];
        $result = $pathsProperty(new AssetsCreator([
            'test',
            'subdir/test',
            '/othercssdir/test',
        ], 'css'));
        $this->assertEquals($expected, $result);

        //Tests plugins
        $this->loadPlugins(['TestPlugin']);
        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css'];
        foreach ([
            'TestPlugin.test',
            'TestPlugin.test.css',
            'TestPlugin./css/test',
            'TestPlugin./css/test.css',
        ] as $path) {
            $this->assertEquals($expected, $pathsProperty(new AssetsCreator($path, 'css')));
        }

        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'subdir' . DS . 'test.css'];
        $this->assertEquals($expected, $pathsProperty(new AssetsCreator('TestPlugin.subdir/test', 'css')));

        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'othercssdir' . DS . 'test.css'];
        $this->assertEquals($expected, $pathsProperty(new AssetsCreator('TestPlugin./othercssdir/test', 'css')));

        //Tests array
        $expected = [
            Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css',
            Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'subdir' . DS . 'test.css',
            Plugin::path('TestPlugin') . 'webroot' . DS . 'othercssdir' . DS . 'test.css',
        ];
        $result = $pathsProperty(new AssetsCreator([
            'TestPlugin.test',
            'TestPlugin.subdir/test',
            'TestPlugin./othercssdir/test',
        ], 'css'));
        $this->assertEquals($expected, $result);
    }

    /**
     * Test for `create()` method, using a css file
     * @test
     */
    public function testCreateWithCss()
    {
        $result = (new AssetsCreator('test', 'css'))->create();
        $this->assertRegExp('/^[\w\d]+$/', $result);

        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'css');
        $this->assertFileExists($file);

        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}';
        $this->assertStringEqualsFile($file, $expected);

        //Tests array
        $result = (new AssetsCreator(['test', 'test2'], 'css'))->create();
        $this->assertRegExp('/^[\w\d]+$/', $result);

        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'css');
        $this->assertFileExists($file);

        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}' .
            '#my-id2{font-size:16px}.my-class2{font-size:18px}';
        $this->assertStringEqualsFile($file, $expected);

        //With no existing target directory
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessageRegExp('/^Failed to create file noExistingDir[\w\d\/\\\\]+\.css$/');
        Configure::write('Assets.target', 'noExistingDir');
        (new AssetsCreator('test', 'css'))->create();
    }

    /**
     * Test for `create()` method, using a js file
     * @test
     */
    public function testCreateWithJs()
    {
        $result = (new AssetsCreator('test', 'js'))->create();
        $this->assertRegExp('/^[\w\d]+$/', $result);

        $expected = 'function other_alert(){alert(\'Another alert\')}' . PHP_EOL .
            '$(function(){var msg=\'Ehi!\';alert(msg)})';
        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'js');
        $this->assertSameAsFile($file, $expected);

        //Tests array
        $result = (new AssetsCreator(['test', 'test2'], 'js'))->create();
        $this->assertRegExp('/^[\w\d]+$/', $result);

        $expected = 'function other_alert(){alert(\'Another alert\')}' . PHP_EOL .
            '$(function(){var msg=\'Ehi!\';alert(msg)});' .
            'var first=\'This is first\';' .
            'var second=\'This is second\';' .
            'alert(first+\' and \'+second)';
        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'js');
        $this->assertSameAsFile($file, $expected);
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
        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'css');
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
     * Test for `path()` method
     * @test
     */
    public function testPath()
    {
        $asset = new AssetsCreator('test', 'css');
        $this->assertEquals(Configure::read('Assets.target') . DS . $asset->create() . '.css', $asset->path());
    }
}
