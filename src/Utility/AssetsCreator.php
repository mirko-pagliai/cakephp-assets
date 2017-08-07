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
     * Asset path
     * @var string
     */
    protected $asset = null;

    /**
     * Paths
     * @var array
     */
    protected $paths = [];

    /**
     * Asset type
     * @var string
     */
    protected $type = null;

    /**
     * Construct. Sets the asset type and paths
     * @param string|array $paths String or array of css files
     * @param string $type Extension (`css` or `js`)
     * @return \Assets\Utility\AssetsCreator
     * @throws InternalErrorException
     * @uses getAssetPath()
     * @uses resolvePath()
     * @uses $asset
     * @uses $paths
     * @uses $type
     */
    public function __construct($paths, $type)
    {
        if (!in_array($type, ['css', 'js'])) {
            throw new InternalErrorException(__d('assets', 'Asset type `{0}` not supported', $type));
        }

        //Note: `resolvePath()` needs `$type`; `getAssetPath()` needs
        //  `$type` and `$paths`
        $this->type = $type;
        $this->paths = $this->resolvePath($paths);
        $this->asset = $this->getAssetPath();

        return $this;
    }

    /**
     * Internal method to resolve partial paths, returning full paths
     * @param string|array $paths Partial paths
     * @return array
     * @throws InternalErrorException
     * @use $type
     */
    protected function resolvePath($paths)
    {
        $loadedPlugins = Plugin::loaded();

        return array_map(function ($path) use ($loadedPlugins) {
            $pluginSplit = pluginSplit($path);

            //Note that using `pluginSplit()` is not sufficient, because
            //  `$path` may still contain a dot
            if (!empty($pluginSplit[0]) && in_array($pluginSplit[0], $loadedPlugins)) {
                list($plugin, $path) = $pluginSplit;
            }

            if (substr($path, 0, 1) === '/') {
                $path = substr($path, 1);
            } else {
                $path = $this->type . DS . $path;
            }

            if (!empty($plugin)) {
                $path = Plugin::path($plugin) . 'webroot' . DS . $path;
            } else {
                $path = WWW_ROOT . $path;
            }

            //Appends the file extension, if not already present
            if (pathinfo($path, PATHINFO_EXTENSION) !== $this->type) {
                $path = sprintf('%s.%s', $path, $this->type);
            }

            if (!file_exists($path)) {
                throw new InternalErrorException(__d('assets', 'File `{0}` doesn\'t exist', str_replace(APP, null, $path)));
            }

            return $path;
        }, (array)$paths);
    }

    /**
     * Internal method to get the asset path
     * @return string
     * @use $paths
     * @use $type
     */
    protected function getAssetPath()
    {
        $basename = md5(serialize(collection($this->paths)->map(function ($path) {
            return [$path, filemtime($path)];
        })->toList()));

        return Configure::read(ASSETS . '.target') . DS . sprintf('%s.%s', $basename, $this->type);
    }

    /**
     * Creates the asset
     * @return string
     * @throws InternalErrorException
     * @uses $asset
     * @uses $paths
     * @uses $type
     */
    public function create()
    {
        if (!is_readable($this->asset)) {
            switch ($this->type) {
                case 'css':
                    $minifier = new Minify\CSS();
                    break;
                case 'js':
                    $minifier = new Minify\JS();
                    break;
            }

            foreach ($this->paths as $path) {
                $minifier->add($path);
            }

            //Writes the file
            if (!(new File($this->asset, false, 0755))->write($minifier->minify())) {
                throw new InternalErrorException(__d('assets', 'Failed to create file {0}', str_replace(APP, null, $this->asset)));
            }
        }

        return pathinfo($this->asset, PATHINFO_FILENAME);
    }
}
