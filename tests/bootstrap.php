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

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Routing\DispatcherFactory;

ini_set('intl.default_locale', 'en_US');
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

define('ROOT', dirname(__DIR__) . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TESTS', ROOT . 'tests');
define('APP', ROOT . 'tests' . DS . 'test_app' . DS);
define('APP_DIR', 'test_app');
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', APP . 'webroot' . DS);
define('TMP', sys_get_temp_dir() . DS . 'cakephp-assets' . DS);
define('CONFIG', APP . 'config' . DS);
define('CACHE', TMP);
define('LOGS', TMP . 'cakephp_log' . DS);
define('SESSIONS', TMP . 'sessions' . DS);
@mkdir(LOGS);
@mkdir(SESSIONS);
@mkdir(CACHE);
@mkdir(CACHE . 'views');
@mkdir(CACHE . 'models');

require dirname(__DIR__) . '/vendor/autoload.php';
require CORE_PATH . 'config' . DS . 'bootstrap.php';

//Disables deprecation warnings for CakePHP >= 3.6
if (version_compare(Configure::version(), '3.6', '>=')) {
    error_reporting(E_ALL ^ E_USER_DEPRECATED);
}

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => APP_DIR,
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT,
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
    'paths' => ['plugins' => [APP . 'Plugin' . DS]],
]);
Configure::write('Asset.timestamp', true);
Configure::write('Assets.force', true);
Configure::write('Assets.target', TMP . 'assets');

Cache::config([
    '_cake_core_' => [
        'engine' => 'File',
        'prefix' => 'cake_core_',
        'serialize' => true,
    ],
]);

Plugin::load('Assets', [
    'bootstrap' => true,
    'path' => ROOT,
    'routes' => true,
]);

DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');

if (function_exists('loadPHPUnitAliases')) {
    loadPHPUnitAliases();
} elseif (!class_exists('PHPUnit\Runner\Version')) {
    class_alias('PHPUnit_Framework_Constraint', 'PHPUnit\Framework\Constraint\Constraint');
}

$_SERVER['PHP_SELF'] = '/';
