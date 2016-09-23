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
 * @see         https://github.com/matthiasmullie/minify
 */
namespace Assets\Utility;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Filesystem\File;
use Cake\Network\Exception\InternalErrorException;
use MatthiasMullie\Minify;

/**
 * An utility to create assets
 */
class AssetsCreator
{
    /**
     * Parses paths and for each path returns an array with the full path and
     * the last modification time
     * @param string|array $paths String or array of css/js files
     * @param string $extension Extension (`css` or `js`)
     * @return array
     * @throws InternalErrorException
     */
    protected static function _parsePaths($paths, $extension)
    {
        $loadedPlugins = Plugin::loaded();

        //Parses paths and for each returns an array with the full path and
        //  the last modification time
        return array_map(function ($path) use ($extension, $loadedPlugins) {
            $pluginSplit = pluginSplit($path);

            //Note that using `pluginSplit()` is not sufficient, because
            //  `$path` may still contain a dot
            if (!empty($pluginSplit[0]) && in_array($pluginSplit[0], $loadedPlugins)) {
                list($plugin, $path) = $pluginSplit;
            }

            if (substr($path, 0, 1) === '/') {
                $path = substr($path, 1);
            } else {
                $path = $extension . DS . $path;
            }

            if (!empty($plugin)) {
                $path = Plugin::path($plugin) . 'webroot' . DS . $path;
            } else {
                $path = WWW_ROOT . $path;
            }

            //Appends the file extension, if not already present
            if (pathinfo($path, PATHINFO_EXTENSION) !== $extension) {
                $path = sprintf('%s.%s', $path, $extension);
            }

            if (!file_exists($path)) {
                throw new InternalErrorException(
                    __d('assets', 'File or directory {0} doesn\'t exist', $path)
                );
            }

            return ['path' => $path, 'time' => filemtime($path)];
        }, (array)$paths);
    }

    /**
     * Gets a css asset. The asset will be created, if doesn't exist
     * @param string|array $paths String or array of css files
     * @return string
     * @throws InternalErrorException
     * @uses _parsePaths()
     */
    public static function css($paths)
    {
        //Parses paths and for each returns an array with the full path and
        //  the last modification time
        $paths = self::_parsePaths($paths, 'css');

        //Sets basename and full path of the asset
        $assetBasename = md5(serialize($paths));
        $assetPath = Configure::read('Assets.target') . DS . sprintf('%s.%s', $assetBasename, 'css');

        //Returns, if the asset already exists
        if (is_readable($assetPath)) {
            return $assetBasename;
        }

        $minifier = new Minify\CSS();

        foreach ($paths as $path) {
            $minifier->add($path['path']);
        }

        //Writes the file
        if (!(new File($assetPath, true, 0777))->write($minifier->minify())) {
            throw new InternalErrorException(
                __d('assets', 'Failed to create file or directory {0}', $assetPath)
            );
        }

        return $assetBasename;
    }

    /**
     * Gets a js asset. The asset will be created, if doesn't exist
     * @param string|array $paths String or array of js files
     * @return string
     * @throws InternalErrorException
     * @uses _parsePaths()
     */
    public static function script($paths)
    {
        //Parses paths and for each returns an array with the full path and
        //  the last modification time
        $paths = self::_parsePaths($paths, 'js');

        //Sets basename and full path of the asset
        $assetBasename = md5(serialize($paths));
        $assetPath = Configure::read('Assets.target') . DS . sprintf('%s.%s', $assetBasename, 'js');

        //Returns, if the asset already exists
        if (is_readable($assetPath)) {
            return $assetBasename;
        }

        $minifier = new Minify\JS();

        foreach ($paths as $path) {
            $minifier->add($path['path']);
        }

        //Writes the file
        if (!(new File($assetPath, true, 0777))->write($minifier->minify())) {
            throw new InternalErrorException(
                __d('assets', 'Failed to create file or directory {0}', $assetPath)
            );
        }

        return $assetBasename;
    }
}
