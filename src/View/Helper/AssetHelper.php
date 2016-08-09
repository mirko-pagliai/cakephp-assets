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
 * @author		Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright	Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 * @see			https://github.com/jakubpawlowicz/clean-css clean-css
 * @see			https://github.com/mishoo/UglifyJS2 UglifyJS
 */
namespace Assets\View\Helper;

use Assets\Utility\Asset;
use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Asset Helper.
 * 
 * This helper allows you to generate assets.  
 * Before using the helper, you have install `clean-css` and `UglifyJS`.
 */
class AssetHelper extends Helper {
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
	 * @uses Assets\Utility\Asset:css()
	 */
	public function css($path, array $options = []) {
		if(!Configure::read('debug') || FORCE_ASSETS) {
			$path = (new Asset())->css($path, 'css');
        }
        
		return $this->Html->css($path, $options);
	}
	
    /**
     * Alias for `script()` method
     * @see script()
     */
    public function js() {
        return call_user_func_array(array(get_class(), 'script'), func_get_args());
    }
	
	/**
     * Compresses and adds js files to the layout
     * @param string|array $url String or array of js files
	 * @param array $options Array of options and HTML attributes
     * @return mixed String of `<script />` tags or NULL if `$inline` is FALSE or if `$once` is TRUE
	 * @uses Assets\Utility\Asset:script()
	 */
	public function script($url, array $options = []) {
		if(!Configure::read('debug') || FORCE_ASSETS) {
			$url = (new Asset())->script($url, 'js');
        }
        
		return $this->Html->script($url, $options);
	}
}