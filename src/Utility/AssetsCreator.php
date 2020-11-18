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
 * @see         https://github.com/matthiasmullie/minify
 */
namespace Assets\Utility;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use InvalidArgumentException;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use Tools\Exceptionist;
use Tools\Filesystem;

/**
 * An utility to create assets
 */
class AssetsCreator
{
    /**
     * Asset full path
     * @var string
     */
    protected $asset;

    /**
     * File paths that will be transformed into a single asset
     * @var array
     */
    protected $paths = [];

    /**
     * Asset type (`css` or `js`)
     * @var string
     */
    protected $type;

    /**
     * Construct. Sets the asset type and paths
     * @param string|array $paths String or array of css files
     * @param string $type Extension (`css` or `js`)
     * @throws \InvalidArgumentException
     * @uses resolveAssetPath()
     * @uses resolveFilePaths()
     * @uses $asset
     * @uses $paths
     * @uses $type
     */
    public function __construct($paths, string $type)
    {
        Exceptionist::inArray([$type, ['css', 'js']], __d('assets', 'Asset type `{0}` not supported', $type), InvalidArgumentException::class);

        //Note: `resolveFilePaths()` method needs `$type` property;
        //  `resolveAssetPath()` method needs `$type` and `$paths` properties
        $this->type = $type;
        $this->paths = $this->resolveFilePaths((array)$paths);
        $this->asset = $this->resolveAssetPath();
    }

    /**
     * Internal method to resolve the asset path
     * @return string Asset full path
     */
    protected function resolveAssetPath(): string
    {
        $basename = md5(serialize(array_map(function (string $path) {
            return [$path, filemtime($path)];
        }, $this->paths)));

        return Configure::read('Assets.target') . DS . $basename . '.' . $this->type;
    }

    /**
     * Internal method to resolve partial file paths and return full paths
     * @param array $paths Partial file paths
     * @return array Full file paths
     */
    protected function resolveFilePaths(array $paths): array
    {
        $loadedPlugins = Plugin::loaded();

        return array_map(function (string $path) use ($loadedPlugins) {
            $pluginSplit = pluginSplit($path);

            //Note that using `pluginSplit()` is not sufficient, because
            //  `$path` may still contain a dot
            if (!empty($pluginSplit[0]) && in_array($pluginSplit[0], $loadedPlugins)) {
                [$plugin, $path] = $pluginSplit;
            }

            $path = string_starts_with($path, '/') ? substr($path, 1) : $this->type . DS . $path;
            $path = DS === '/' ? $path : $path = str_replace('/', DS, $path);
            $path = empty($plugin) ? WWW_ROOT . $path : Plugin::path($plugin) . 'webroot' . DS . $path;

            //Appends the file extension, if not already present
            $path = pathinfo($path, PATHINFO_EXTENSION) == $this->type ? $path : sprintf('%s.%s', $path, $this->type);

            return Exceptionist::isReadable($path);
        }, $paths);
    }

    /**
     * Creates the asset
     * @return string
     * @uses path()
     * @uses $paths
     * @uses $type
     */
    public function create(): string
    {
        $path = $this->path();

        if (!file_exists($path) || !is_readable($path)) {
            $minifier = $this->type === 'css' ? new CSS() : new JS();
            array_map([$minifier, 'add'], $this->paths);

            //Writes the file
            $Filesystem = new Filesystem();
            $success = $Filesystem->createFile($path, $minifier->minify(), 0777, true);
            Exceptionist::isTrue($success, __d('assets', 'Failed to create file {0}', $Filesystem->rtr($this->path())));
        }

        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Returns the asset full path
     * @return string Asset full path
     */
    public function path(): string
    {
        return $this->asset;
    }
}
