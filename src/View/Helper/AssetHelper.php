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
     * @uses Assets\Utility\AssetsCreator::path()
     */
    protected function path($path, $type)
    {
        if (Configure::read('debug') && !Configure::read('Assets.force')) {
            return $path;
        }

        $asset = new AssetsCreator($path, $type);
        $path = '/assets/' . $asset->create();

        //Appends the timestamp
        $stamp = Configure::read('Asset.timestamp');
        if ($stamp === 'force' || ($stamp === true && Configure::read('debug'))) {
            $path = sprintf('%s.%s?%s', $path, $type, filemtime($asset->path()));
        }

        return $path;
    }

    /**
     * Compresses and adds a css file to the layout
     * @param string|array $path String or array of css files
     * @param array $options Array of options and HTML attributes
     * @return string Html, `<link>` or `<style>` tag
     * @uses path()
     */
    public function css($path, array $options = [])
    {
        return $this->Html->css($this->path($path, 'css'), $options);
    }

    /**
     * Compresses and adds js files to the layout
     * @param string|array $url String or array of js files
     * @param array $options Array of options and HTML attributes
     * @return mixed String of `<script />` tags or null if `$inline` is
     *  false or if `$once` is true
     * @uses path()
     */
    public function script($url, array $options = [])
    {
        return $this->Html->script($this->path($url, 'js'), $options);
    }
}
