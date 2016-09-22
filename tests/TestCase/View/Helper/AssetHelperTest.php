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

use Assets\View\Helper\AssetHelper;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;

/**
 * AssetHelperTest class
 */
class AssetHelperTest extends TestCase
{
    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Configure::write('debug', true);
        Configure::write('Assets.force', true);

        $this->View = new View();
        $this->Asset = new AssetHelper($this->View);
        $this->Html = new HtmlHelper($this->View);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->Asset, $this->Html, $this->View);

        //Deletes all assets
        foreach (glob(Configure::read('Assets.target') . DS . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Test for `css()` method
     * @return void
     * @test
     */
    public function testCss()
    {
        $regex = '/href="\/assets\/css\/[a-z0-9]+\.css"/';

        $result = $this->Asset->css('test');
        $expected = [
            'link' => [
                'rel' => 'stylesheet',
                'href',
            ],
        ];
        $this->assertHtml($expected, $result);
        $this->assertRegExp($regex, $result);

        $result = $this->Asset->css(['test', 'test2']);
        $expected = [
            'link' => [
                'rel' => 'stylesheet',
                'href',
            ],
        ];
        $this->assertHtml($expected, $result);
        $this->assertRegExp($regex, $result);
    }

    /**
     * Test for `css()` method with `debug` enabled/disabled
     * @return void
     * @test
     */
    public function testCssWithDebug()
    {
        //`force`  disabled, so it does not affect the test
        Configure::write('Assets.force', false);

        Configure::write('debug', true);

        $result = $this->Asset->css('test');
        $this->assertEquals($this->Html->css('test'), $result);

        Configure::write('debug', false);

        $result = $this->Asset->css('test');
        $this->assertNotEquals($this->Html->css('test'), $result);
    }

    /**
     * Test for `css()` method with `force` enabled/disabled
     * @return void
     * @test
     */
    public function testCssWithForce()
    {
        //Debugging disabled, so it does not affect the test
        Configure::write('debug', true);

        Configure::write('Assets.force', false);

        $result = $this->Asset->css('test');
        $this->assertEquals($this->Html->css('test'), $result);

        Configure::write('Assets.force', true);

        $result = $this->Asset->css('test');
        $this->assertNotEquals($this->Html->css('test'), $result);
    }

    /**
     * Test for `script()` method
     * @return void
     * @test
     */
    public function testScript()
    {
        $regex = '/src="\/assets\/js\/[a-z0-9]+\.js"/';

        $result = $this->Asset->script('test');
        $expected = ['script' => ['src']];
        $this->assertHtml($expected, $result);
        $this->assertRegExp($regex, $result);

        $result = $this->Asset->script(['test', 'test2']);
        $expected = ['script' => ['src']];
        $this->assertHtml($expected, $result);
        $this->assertRegExp($regex, $result);
    }
}
