<?php
/**
 * This file is part of Assets.
 *
 * Assets is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * Assets is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Assets.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
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
    //@codingStandardsIgnoreLine
    @mkdir($target);
}

if (!is_writeable($target)) {
    trigger_error(sprintf('Directory %s not writeable', $target), E_USER_ERROR);
}
