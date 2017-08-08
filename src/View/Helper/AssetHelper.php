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
     * Compresses and adds a css file to the layout
     * @param string|array $path String or array of css files
     * @param array $options Array of options and HTML attributes
     * @return string Html, `<link>` or `<style>` tag
     * @uses Assets\Utility\AssetsCreator:css()
     */
    public function css($path, array $options = [])
    {
        if (!Configure::read('debug') || Configure::read(ASSETS . '.force')) {
            $path = '/assets/' . (new AssetsCreator($path, 'css'))->create();
        }

        return $this->Html->css($path, $options);
    }

    /**
     * Compresses and adds js files to the layout
     * @param string|array $url String or array of js files
     * @param array $options Array of options and HTML attributes
     * @return mixed String of `<script />` tags or null if `$inline` is
     *  false or if `$once` is true
     * @uses Assets\Utility\AssetsCreator:script()
     */
    public function script($url, array $options = [])
    {
        if (!Configure::read('debug') || Configure::read(ASSETS . '.force')) {
            $url = '/assets/' . (new AssetsCreator($url, 'js'))->create();
        }

        return $this->Html->script($url, $options);
    }
}
