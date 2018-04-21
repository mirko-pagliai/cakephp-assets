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
use Cake\Core\Configure;

//Sets the default Assets name
if (!defined('ASSETS')) {
    define('ASSETS', 'Assets');
}

//If `true`, assets will be used even if debugging is enabled
if (!Configure::check(ASSETS . '.force')) {
    Configure::write(ASSETS . '.force', false);
}

//Default assets directory
if (!Configure::check(ASSETS . '.target')) {
    Configure::write(ASSETS . '.target', TMP . 'assets');
}

//Checks for target directory
$target = Configure::read(ASSETS . '.target');

if (!file_exists($target)) {
    safe_mkdir($target);
}

if (!is_writeable($target)) {
    trigger_error(sprintf('Directory %s not writeable', $target), E_USER_ERROR);
}
