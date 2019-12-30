<?php
declare(strict_types=1);

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
namespace Assets\Test\TestCase\TestSuite;

use Assets\TestSuite\TestCase;
use Cake\Core\Configure;

/**
 * TestCaseTest class
 */
class TestCaseTest extends TestCase
{
    /**
     * Test for `tearDown()` method
     * @test
     */
    public function testTearDown()
    {
        Configure::delete('Assets.target');
        $this->assertNull(parent::tearDown());
    }
}
