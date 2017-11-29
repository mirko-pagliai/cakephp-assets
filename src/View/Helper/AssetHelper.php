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
namespace Assets\View\Helper;

use Assets\Utility\AssetsCreator;
use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Asset Helper.
 *
 * This helper allows you to generate assets.
 */
class AssetHelper extends Helper
{
    /**
     * Helpers
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Gets the asset path
     * @param string|array $path String or array of css/js files
     * @param string $type `css` or `js`
     * @return string Asset path
     * @uses Assets\Utility\AssetsCreator::create()
     * @uses Assets\Utility\AssetsCreator::getAssetFilename()
     * @uses Assets\Utility\AssetsCreator::getAssetPath()
     */
    protected function getAssetPath($path, $type)
    {
        if (Configure::read('debug') && !Configure::read(ASSETS . '.force')) {
            return $path;
        }

        $asset = new AssetsCreator($path, $type);
        $asset->create();
        $path = '/assets/' . $asset->getAssetFilename();

        //Appends the timestamp
        $stamp = Configure::read('Asset.timestamp');
        $timestampEnabled = $stamp === 'force' || ($stamp === true && Configure::read('debug'));
        if ($timestampEnabled) {
            $path .= '.' . $type . '?' . filemtime($asset->getAssetPath());
        }

        return $path;
    }

    /**
     * Compresses and adds a css file to the layout
     * @param string|array $path String or array of css files
     * @param array $options Array of options and HTML attributes
     * @return string Html, `<link>` or `<style>` tag
     * @uses getAssetPath()
     */
    public function css($path, array $options = [])
    {
        $path = $this->getAssetPath($path, 'css');

        return $this->Html->css($path, $options);
    }

    /**
     * Compresses and adds js files to the layout
     * @param string|array $url String or array of js files
     * @param array $options Array of options and HTML attributes
     * @return mixed String of `<script />` tags or null if `$inline` is
     *  false or if `$once` is true
     * @uses getAssetPath()
     */
    public function script($url, array $options = [])
    {
        $url = $this->getAssetPath($url, 'js');

        return $this->Html->script($url, $options);
    }
}
