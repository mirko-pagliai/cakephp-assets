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

use Cake\Network\Exception\InternalErrorException;

require_once 'global_functions.php';
require_once 'constants.php';

if (!CLEANCSS_BIN) {
    throw new InternalErrorException(sprintf('The executable file for %s was not found', 'cleancss'));
}

if (!UGLIFYJS_BIN) {
    throw new InternalErrorException(sprintf('The executable file for %s was not found', 'uglifyjs'));
}

if (!is_writeable(ASSETS)) {
    throw new InternalErrorException(sprintf('File or directory %s not writeable', ASSETS));
}
