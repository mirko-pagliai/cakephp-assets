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

use Cake\Core\Configure;

//If `true`, assets will be used even if debugging is enabled
if (!Configure::check('Assets.force')) {
    Configure::write('Assets.force', false);
}

//Default assets directory
if (!Configure::check('Assets.target')) {
    Configure::write('Assets.target', TMP . 'assets');
}

//Checks for target directory
$target = Configure::read('Assets.target');
if (!file_exists($target)) {
    mkdir($target, 0777, true);
}
if (!is_dir($target) || !is_writeable($target)) {
    trigger_error(sprintf('The directory `%s` is not writable or is not a directory', $target), E_USER_ERROR);
}