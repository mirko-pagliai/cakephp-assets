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

        Configure::write('debug', true);
        Configure::write(ASSETS . '.force', true);

        $view = new View();
        $this->Asset = new AssetHelper($view);
        $this->Html = new HtmlHelper($view);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        //Deletes all assets
        foreach (glob(Configure::read(ASSETS . '.target') . DS . '*') as $file) {
            unlink($file);
        }

        unset($this->Asset, $this->Html);
    }

    /**
     * Test for `css()` method
     * @test
     */
    public function testCss()
    {
        $regex = '/href="\/assets\/[a-z0-9]+\.css"/';

        $result = $this->Asset->css('test');
        $expected = ['link' => ['rel' => 'stylesheet', 'href']];
        $this->assertHtml($expected, $result);
        $this->assertRegExp($regex, $result);

        $result = $this->Asset->css(['test', 'test2']);
        $expected = ['link' => ['rel' => 'stylesheet', 'href']];
        $this->assertHtml($expected, $result);
        $this->assertRegExp($regex, $result);
    }

    /**
     * Test for `css()` method with `debug` enabled/disabled
     * @test
     */
    public function testCssWithDebug()
    {
        //`force`  disabled, so it does not affect the test
        Configure::write(ASSETS . '.force', false);

        Configure::write('debug', true);

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
        Configure::write('debug', true);

        Configure::write(ASSETS . '.force', false);

        $result = $this->Asset->css('test');
        $this->assertEquals($this->Html->css('test'), $result);

        Configure::write(ASSETS . '.force', true);

        $result = $this->Asset->css('test');
        $this->assertNotEquals($this->Html->css('test'), $result);
    }

    /**
     * Test for `script()` method
     * @test
     */
    public function testScript()
    {
        $regex = '/src="\/assets\/[a-z0-9]+\.js"/';

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
