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

//Sets the default asset directory
if (!defined('ASSETS')) {
    define('ASSETS', WWW_ROOT . 'assets');
}

//Sets the default asset address
if (!defined('ASSETS_WWW')) {
    define('ASSETS_WWW', '/assets');
}

//If `false`, assets will be used only if debugging is off. Else, if `true`,
//  assets will be always used
if (!defined('FORCE_ASSETS')) {
    define('FORCE_ASSETS', false);
}

//Sets the cleancss executable
if (!defined('CLEANCSS_BIN')) {
    define('CLEANCSS_BIN', exec(sprintf('which %s', ('cleancss'))));
}

//Sets the uglifyjs executable
if (!defined('UGLIFYJS_BIN')) {
    define('UGLIFYJS_BIN', exec(sprintf('which %s', ('uglifyjs'))));
}
