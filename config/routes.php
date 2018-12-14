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
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin('Assets', ['path' => '/assets'], function (RouteBuilder $routes) {
    $routes->connect('/:filename', ['controller' => 'Assets', 'action' => 'asset'], [
        'filename' => '[\d\w]+\.(css|js)',
        'pass' => ['filename'],
    ]);
});
