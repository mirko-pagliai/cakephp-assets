<?php
declare(strict_types=1);

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
    public function testConstruct(): void
    {
        $asset = new AssetsCreator('test', 'css');
        $this->assertEquals('css', $this->getProperty($asset, 'type'));

        $paths = $this->getProperty($asset, 'paths');
        $this->assertCount(1, $paths);
        $this->assertEquals(WWW_ROOT . 'css', dirname($paths[0]));
        $this->assertEquals('test.css', basename($paths[0]));

        $asset = $this->getProperty($asset, 'asset');
        $this->assertEquals(Configure::read('Assets.target'), dirname($asset));
        $this->assertMatchesRegularExpression('/^\w+\.css$/', basename($asset));

        //With unsupported type
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Asset type `html` not supported');
        new AssetsCreator('', 'html');
    }

    /**
     * Test for `resolveAssetPath()` method
     * @test
     */
    public function testResolveAssetPath(): void
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
        $this->loadPlugins(['TestPlugin' => []]);
        $expected = Configure::read('Assets.target') . DS . sprintf('%s.%s', md5(serialize([[
            $file = Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css',
            filemtime($file),
        ]])), 'css');
        $this->assertEquals($expected, $resolveAssetPathMethod((new AssetsCreator('TestPlugin.test', 'css'))));
    }

    /**
     * Test for `resolveFilePaths()` method
     * @test
     */
    public function testResolveFilePaths(): void
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
            'sub-dir/test' => [WWW_ROOT . 'css' . DS . 'sub-dir' . DS . 'test.css'],
            '/other-css-dir/test' => [WWW_ROOT . 'other-css-dir' . DS . 'test.css'],
        ] as $path => $expected) {
            $this->assertEquals($expected, $pathsProperty(new AssetsCreator($path, 'css')));
        }

        //Tests array
        $expected = [
            WWW_ROOT . 'css' . DS . 'test.css',
            WWW_ROOT . 'css' . DS . 'sub-dir' . DS . 'test.css',
            WWW_ROOT . 'other-css-dir' . DS . 'test.css',
        ];
        $result = $pathsProperty(new AssetsCreator([
            'test',
            'sub-dir/test',
            '/other-css-dir/test',
        ], 'css'));
        $this->assertEquals($expected, $result);

        //Tests plugins
        $this->loadPlugins(['TestPlugin' => []]);
        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css'];
        foreach ([
            'TestPlugin.test',
            'TestPlugin.test.css',
            'TestPlugin./css/test',
            'TestPlugin./css/test.css',
        ] as $path) {
            $this->assertEquals($expected, $pathsProperty(new AssetsCreator($path, 'css')));
        }

        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'sub-dir' . DS . 'test.css'];
        $this->assertEquals($expected, $pathsProperty(new AssetsCreator('TestPlugin.sub-dir/test', 'css')));

        $expected = [Plugin::path('TestPlugin') . 'webroot' . DS . 'other-css-dir' . DS . 'test.css'];
        $this->assertEquals($expected, $pathsProperty(new AssetsCreator('TestPlugin./other-css-dir/test', 'css')));

        //Tests array
        $expected = [
            Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'test.css',
            Plugin::path('TestPlugin') . 'webroot' . DS . 'css' . DS . 'sub-dir' . DS . 'test.css',
            Plugin::path('TestPlugin') . 'webroot' . DS . 'other-css-dir' . DS . 'test.css',
        ];
        $result = $pathsProperty(new AssetsCreator([
            'TestPlugin.test',
            'TestPlugin.sub-dir/test',
            'TestPlugin./other-css-dir/test',
        ], 'css'));
        $this->assertEquals($expected, $result);
    }

    /**
     * Test for `create()` method, using a css file
     * @uses \Assets\Utility\AssetsCreator::create()
     * @test
     */
    public function testCreateWithCss(): void
    {
        $result = (new AssetsCreator('test', 'css'))->create();
        $this->assertMatchesRegularExpression('/^\w+$/', $result);

        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'css');
        $this->assertFileExists($file);

        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}';
        $this->assertStringEqualsFile($file, $expected);

        //Tests array
        $result = (new AssetsCreator(['test', 'test2'], 'css'))->create();
        $this->assertMatchesRegularExpression('/^\w+$/', $result);

        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'css');
        $this->assertFileExists($file);

        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}' .
            '#my-id2{font-size:16px}.my-class2{font-size:18px}';
        $this->assertStringEqualsFile($file, $expected);

        //With no existing target directory
        $this->skipIf(IS_WIN);
        $this->expectExceptionMessageMatches('/^Failed to create file [\w\d\/\\\\]+\.css$/');
        Configure::write('Assets.target', DS . 'noExistingDir');
        (new AssetsCreator('test', 'css'))->create();
    }

    /**
     * Test for `create()` method, using a js file
     * @uses \Assets\Utility\AssetsCreator::create()
     * @test
     */
    public function testCreateWithJs(): void
    {
        $result = (new AssetsCreator('test', 'js'))->create();
        $this->assertMatchesRegularExpression('/^\w+$/', $result);

        $expected = 'function otherAlert(){alert("Another alert")}' . PHP_EOL .
            '$(()=>{const msg="Ehi!";alert(msg)})';
        $file = Configure::read('Assets.target') . DS . $result . '.js';
        $this->assertSameAsFile($file, $expected);

        //Tests array
        $result = (new AssetsCreator(['test', 'test2'], 'js'))->create();
        $this->assertMatchesRegularExpression('/^\w+$/', $result);

        $expected = 'function otherAlert(){alert("Another alert")}' . PHP_EOL .
            '$(()=>{const msg="Ehi!";alert(msg)});' .
            'const first="This is first";' .
            'const second="This is second";' .
            'alert(first+" and "+second)';
        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'js');
        $this->assertSameAsFile($file, $expected);
    }

    /**
     * Test for `create()` method. The asset is created only if not exist
     * @uses \Assets\Utility\AssetsCreator::create()
     * @test
     */
    public function testCreateReturnsExistingAsset(): void
    {
        //Creates the asset
        $result = (new AssetsCreator('test', 'css'))->create();

        //Sets the file path and the creation time
        $file = Configure::read('Assets.target') . DS . sprintf('%s.%s', $result, 'css');
        $time = filemtime($file);

        //Tries to create again the same asset. Now the creation time is the same
        (new AssetsCreator('test', 'css'))->create();
        $this->assertEquals($time, filemtime($file));

        //Deletes asset and wait 1 second
        unlink($file);
        sleep(1);

        //Tries to create again the same asset. Now the creation time is different
        (new AssetsCreator('test', 'css'))->create();
        $this->assertNotEquals($time, filemtime($file));
    }

    /**
     * Test for `path()` method
     * @test
     */
    public function testPath(): void
    {
        $asset = new AssetsCreator('test', 'css');
        $this->assertEquals(Configure::read('Assets.target') . DS . $asset->create() . '.css', $asset->path());
    }
}
