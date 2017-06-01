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
namespace Assets\Controller;

use Assets\Network\Exception\AssetNotFoundException;
use Cake\Controller\Controller;
use Cake\Core\Configure;

/**
 * Assets controller class
 */
class AssetsController extends Controller
{
    /**
     * Renders an asset
     * @param string $filename Asset filename
     * @return Cake\Network\Response|null
     * @throws AssetNotFoundException
     */
    public function asset($filename)
    {
        $file = Configure::read(ASSETS . '.target') . DS . $filename;

        if (!is_readable($file)) {
            throw new AssetNotFoundException(__d('assets', 'File `{0}` doesn\'t exist', $file));
        }

        return $this->response->withFile($file)->withType(pathinfo($file, PATHINFO_EXTENSION));
    }
}
