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
 * @see         https://github.com/jakubpawlowicz/clean-css clean-css
 * @see         https://github.com/mishoo/UglifyJS2 UglifyJS
 */
namespace Assets\Utility;

use Cake\Core\Plugin;
use Cake\Filesystem\File;
use Cake\Network\Exception\InternalErrorException;

/**
 * An utility to create assets
 */
class Asset
{
    /**
     * Parses paths and for each path returns an array with the full path and
     * the last modification time
     * @param string|array $paths String or array of css/js files
     * @param string $extension Extension (`css` or `js`)
     * @return array
     * @uses Cake\Core\Plugin::loaded()
     * @uses Cake\Core\Plugin::path()
     */
    protected function _parsePaths($paths, $extension)
    {
        $plugins = Plugin::loaded();

        //Parses paths and for each returns an array with the full path and
        //  the last modification time
        $paths = array_map(function ($path) use ($extension, $plugins) {
            $plugin = pluginSplit($path);

            if (in_array($plugin[0], $plugins)) {
                $path = $plugin[1];
            }

            if (substr($path, 0, 1) === '/') {
                $path = substr($path, 1);
            } else {
                $path = $extension . DS . $path;
            }

            if (in_array($plugin[0], $plugins)) {
                $path = Plugin::path($plugin[0]) . 'webroot' . DS . $path;
            } else {
                $path = WWW_ROOT . $path;
            }

            $path = sprintf('%s.%s', $path, $extension);

            return [$path, filemtime($path)];
        }, is_array($paths) ? $paths : [$paths]);

        return $paths;
    }

    /**
     * Gets a css asset. The asset will be created, if doesn't exist
     * @param string|array $path String or array of css files
     * @return string Asset address
     * @see https://github.com/jakubpawlowicz/clean-css clean-css
     * @throws InternalErrorException
     * @uses _parsePaths()
     */
    public function css($path)
    {
        //Parses paths and for each returns an array with the full path and
        //  the last modification time
        $path = $this->_parsePaths($path, 'css');
        //Sets the basename
        $filename = sprintf('%s.%s', md5(serialize($path)), 'css');
        //Sets the asset file
        $asset = ASSETS . DS . $filename;
        //Sets the asset address
        $www = ASSETS_WWW . '/' . $filename;

        //Returns, if the asset already exists
        if (is_readable($asset)) {
            return $www;
        }

        //Reads the content of all paths
        $content = implode(PHP_EOL, array_map(function ($path) {
            return file_get_contents($path[0]);
        }, $path));

        //Writes the file
        if (!(new File($asset, true, 0777))->write($content, 'w', true)) {
            throw new InternalErrorException(__d('assets', 'Failed to create file or directory {0}', $asset));
        }

        //Executes `cleancss`
        exec(sprintf('%s -o %s --s0 %s', CLEANCSS_BIN, $asset, $asset));

        return $www;
    }

    /**
     * Gets a js asset. The asset will be created, if doesn't exist
     * @param string|array $path String or array of js files
     * @return string Asset address
     * @see https://github.com/mishoo/UglifyJS2 UglifyJS
     * @throws InternalErrorException
     * @uses _parsePaths()
     */
    public function script($path)
    {
        //Parses paths and for each returns an array with the full path and
        //  the last modification time
        $path = $this->_parsePaths($path, 'js');
        //Sets the basename
        $filename = sprintf('%s.%s', md5(serialize($path)), 'js');
        //Sets the asset file
        $asset = ASSETS . DS . $filename;
        //Sets the asset address
        $www = ASSETS_WWW . '/' . $filename;

        //Returns, if the asset already exists
        if (is_readable($asset)) {
            return $www;
        }

        //Reads the content of all paths
        $content = implode(PHP_EOL, array_map(function ($path) {
            return file_get_contents($path[0]);
        }, $path));

        //Writes the file
        if (!(new File($asset, true, 0777))->write($content, 'w', true)) {
            throw new InternalErrorException(__d('assets', 'Failed to create file or directory {0}', $asset));
        }

        //Executes `uglifyjs`
        exec(sprintf('%s %s --compress --mangle -o %s', UGLIFYJS_BIN, $asset, $asset));

        return $www;
    }
}
