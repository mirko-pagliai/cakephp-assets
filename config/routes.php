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

use Assets\Middleware\AssetMiddleware;
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Assets', function (RouteBuilder $routes): void {
    $routes->registerMiddleware('asset', AssetMiddleware::class);
    $routes->get('/{filename}', [])
        ->setPatterns(['filename' => '[\w-]+\.(css|js)'])
        ->setPass(['filename'])
        ->setMiddleware(['asset']);
});
