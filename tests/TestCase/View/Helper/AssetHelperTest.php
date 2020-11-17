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
namespace Assets\Test\TestCase\View\Helper;

use Assets\TestSuite\TestCase;
use Assets\View\Helper\AssetHelper;
use Cake\Core\Configure;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;

/**
 * AssetHelperTest class
 */
class AssetHelperTest extends TestCase
{
    /**
     * @var \Assets\View\Helper\AssetHelper
     */
    protected $Asset;

    /**
     * @var \Cake\View\Helper\HtmlHelper
     */
    protected $Html;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Configure::write([
            'debug' => true,
            'Assets.force' => true,
            'Assets.timestamp' => true,
        ]);

        $this->Asset = $this->Asset ?: new AssetHelper(new View());
        $this->Html = $this->Html ? : new HtmlHelper(new View());
    }

    /**
     * Test for `css()` method
     * @test
     */
    public function testCss()
    {
        $expected = ['link' => [
            'rel' => 'stylesheet',
            'href' => 'preg:/\/assets\/[\w\d]+\.css\?\d{10}/',
        ]];
        $this->assertHtml($expected, $this->Asset->css('test'));
        $this->assertHtml($expected, $this->Asset->css(['test', 'test2']));
    }

    /**
     * Test for `css()` method with `debug` enabled/disabled
     * @test
     */
    public function testCssWithDebug()
    {
        //`force`  disabled, so it does not affect the test
        Configure::write('Assets.force', false);
        $this->assertEquals($this->Html->css('test'), $this->Asset->css('test'));

        Configure::write('debug', false);
        $this->assertNotEquals($this->Html->css('test'), $this->Asset->css('test'));
    }

    /**
     * Test for `css()` method with `force` enabled/disabled
     * @test
     */
    public function testCssWithForce()
    {
        //Debugging disabled, so it does not affect the test
        Configure::write('Assets.force', false);
        $this->assertEquals($this->Html->css('test'), $this->Asset->css('test'));

        Configure::write('Assets.force', true);
        $this->assertNotEquals($this->Html->css('test'), $this->Asset->css('test'));
    }

    /**
     * Test for `css()` method with `timestamp` enabled/disabled
     * @test
     */
    public function testCssWithTimestamp()
    {
        $expected = ['link' => [
            'rel' => 'stylesheet',
            'href' => 'preg:/\/assets\/[\w\d]+\.css\?\d{10}/',
        ]];
        $this->assertHtml($expected, $this->Asset->css('test'));

        //Timestamp disabled, so it does not affect the test
        Configure::write('Asset.timestamp', false);
        $expected = ['link' => [
            'rel' => 'stylesheet',
            'href' => 'preg:/\/assets\/[\w\d]+\.css/',
        ]];
        $this->assertHtml($expected, $this->Asset->css('test'));
    }

    /**
     * Test for `script()` method
     * @test
     */
    public function testScript()
    {
        $expected = ['script' => ['src' => 'preg:/\/assets\/[\w\d]+\.js\?\d{10}/']];
        $this->assertHtml($expected, $this->Asset->script('test'));
        $this->assertHtml($expected, $this->Asset->script(['test', 'test2']));
    }
}
