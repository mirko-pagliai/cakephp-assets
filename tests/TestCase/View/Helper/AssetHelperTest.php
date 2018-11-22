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
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
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

        $this->Asset = new AssetHelper(new View);
        $this->Html = new HtmlHelper(new View);
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

        $result = $this->Asset->css('test');
        $this->assertEquals($this->Html->css('test'), $result);

        Configure::write('debug', false);

        $result = $this->Asset->css('test');
        $this->assertNotEquals($this->Html->css('test'), $result);
    }

    /**
     * Test for `css()` method with `force` enabled/disabled
     * @test
     */
    public function testCssWithForce()
    {
        //Debugging disabled, so it does not affect the test
        Configure::write('Assets.force', false);

        $result = $this->Asset->css('test');
        $this->assertEquals($this->Html->css('test'), $result);

        Configure::write('Assets.force', true);

        $result = $this->Asset->css('test');
        $this->assertNotEquals($this->Html->css('test'), $result);
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
