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
namespace Assets\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;

class AssetsCreator extends \Assets\Utility\AssetsCreator
{
    public static function parsePaths($paths, $extension)
    {
        return parent::_parsePaths($paths, $extension);
    }
}

/**
 * AssetsCreatorTest class
 */
class AssetsCreatorTest extends TestCase
{
    /**
     * Test for `_parsePaths()` method
     * @return void
     * @uses AssetsCreator::parsePaths()
     * @test
     */
    public function testParsePaths()
    {
        $result = AssetsCreator::parsePaths('test', 'css');
        $expected = [
            [
                $file = APP . 'webroot' . DS . 'css' . DS . 'test.css',
                filemtime($file),
            ],
        ];
        $this->assertEquals($expected, $result);

        $result = AssetsCreator::parsePaths('/css/test', 'css');
        $this->assertEquals($expected, $result);

        $result = AssetsCreator::parsePaths([
            'test',
            'subdir/test',
            '/othercssdir/test',
            'TestPlugin.test',
            'TestPlugin.subdir/test',
            'TestPlugin./othercssdir/test',
        ], 'css');
        $expected = [
            APP . 'webroot' . DS . 'css' . DS . 'test.css',
            APP . 'webroot' . DS . 'css' . DS . 'subdir' . DS . 'test.css',
            APP . 'webroot' . DS . 'othercssdir' . DS . 'test.css',
            APP . 'Plugin' . DS . 'TestPlugin' . DS . 'webroot' . DS . 'css' .
                DS . 'test.css',
            APP . 'Plugin' . DS . 'TestPlugin' . DS . 'webroot' . DS . 'css' .
                DS . 'subdir' . DS . 'test.css',
            APP . 'Plugin' . DS . 'TestPlugin' . DS . 'webroot' . DS .
                'othercssdir' . DS . 'test.css',
        ];

        $expected = array_map(function ($file) {
            return [$file, filemtime($file)];
        }, $expected);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test for `_parsePaths()` method, with no existing file
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @return void
     * @uses AssetsCreator::parsePaths()
     * @test
     */
    public function testParsePathsNoExistingFile()
    {
        AssetsCreator::parsePaths('noExistingFile', 'css');
    }

    /**
     * Test for `_parsePaths()` method, with no existing plugin
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @return void
     * @uses AssetsCreator::parsePaths()
     * @test
     */
    public function testParsePathsNoExistingPlugin()
    {
        AssetsCreator::parsePaths('noExistingPlugin.test', 'css');
    }

    /**
     * Test for `_parsePaths()` method, with no existing file from plugin
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @return void
     * @uses AssetsCreator::parsePaths()
     * @test
     */
    public function testParsePathsNoExistingPluginFile()
    {
        AssetsCreator::parsePaths('TestPlugin.noExistingFile', 'css');
    }

    /**
     * Test for `css()` method
     * @return void
     * @test
     */
    public function testCss()
    {
        $result = AssetsCreator::css('test');
        $expected = md5(serialize([
            [
                $file = APP . 'webroot' . DS . 'css' . DS . 'test.css',
                filemtime($file),
            ],
        ]));
        $this->assertEquals($expected, $result);

        $file = ASSETS . DS . sprintf('%s.%s', $result, 'css');
        $this->assertTrue(file_exists($file));

        $result = file_get_contents($file);
        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}';
        $this->assertEquals($expected, $result);

        //@codingStandardsIgnoreLine
        @unlink($file);

        $result = AssetsCreator::css(['test', 'test2']);
        $expected = md5(serialize([
            [
                $file = APP . 'webroot' . DS . 'css' . DS . 'test.css',
                filemtime($file),
            ],
            [
                $file = APP . 'webroot' . DS . 'css' . DS . 'test2.css',
                filemtime($file),
            ],
        ]));
        $this->assertEquals($expected, $result);

        $file = ASSETS . DS . sprintf('%s.%s', $result, 'css');
        $this->assertTrue(file_exists($file));

        $result = file_get_contents($file);
        $expected = '#my-id{font-size:12px}.my-class{font-size:14px}' .
            '#my-id2{font-size:16px}.my-class2{font-size:18px}';
        $this->assertEquals($expected, $result);

        //@codingStandardsIgnoreLine
        @unlink($file);
    }

    /**
     * Test for `script()` method
     * @return void
     * @test
     */
    public function testScript()
    {
        $result = AssetsCreator::script('test');
        $expected = md5(serialize([
            [
                $file = APP . 'webroot' . DS . 'js' . DS . 'test.js',
                filemtime($file),
            ],
        ]));
        $this->assertEquals($expected, $result);

        $file = ASSETS . DS . sprintf('%s.%s', $result, 'js');
        $this->assertTrue(file_exists($file));

        $result = file_get_contents($file);
        $expected = 'function other_alert(){alert("Another alert")}' .
            '$(function(){var t="Ehi!";alert(t)});';
        $this->assertEquals($expected, $result);

        //@codingStandardsIgnoreLine
        @unlink($file);

        $result = AssetsCreator::script(['test', 'test2']);
        $expected = md5(serialize([
            [
                $file = APP . 'webroot' . DS . 'js' . DS . 'test.js',
                filemtime($file),
            ],
            [
                $file = APP . 'webroot' . DS . 'js' . DS . 'test2.js',
                filemtime($file),
            ],
        ]));
        $this->assertEquals($expected, $result);

        $file = ASSETS . DS . sprintf('%s.%s', $result, 'js');
        $this->assertTrue(file_exists($file));

        $result = file_get_contents($file);
        $expected = 'function other_alert(){alert("Another alert")}' .
            '$(function(){var r="Ehi!";alert(r)});' .
            'var first="This is first",second="This is second";' .
            'alert(first+" and "+second);';
        $this->assertEquals($expected, $result);

        //@codingStandardsIgnoreLine
        @unlink($file);
    }
}
