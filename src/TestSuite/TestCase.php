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
 * @since       1.1.8
 */
namespace Assets\TestSuite;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase as CakeTestCase;
use Tools\ReflectionTrait;

/**
 * TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    use ReflectionTrait;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadPlugins(['Assets']);
    }

    /**
     * Called after every test method
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        safe_unlink_recursive(Configure::read('Assets.target'));
    }
}
