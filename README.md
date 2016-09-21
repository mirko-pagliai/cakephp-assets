# Assets
*Assets* is a CakePHP plugin to allows you to generate assets.  
It uses [matthiasmullie/minify](https://github.com/matthiasmullie/minify) and
provides a convniente helper to generate and link assets files.

## Installation
You can install the plugin via composer:

    $ composer require --prefer-dist mirko-pagliai/assets
    
You have to edit `APP/config/bootstrap.php` to load the plugin:

    Plugin::load('Assets', ['bootstrap' => true, 'routes' => true]);

For more information on how to load the plugin, please refer to the 
[Cookbook](http://book.cakephp.org/3.0/en/plugins.html#loading-a-plugin).
    
By default the plugin uses the `APP/tmp/assets` directory to save the 
asset files. So you have to create the directory and make it writable:

    $ mkdir tmp/assets && chmod 775 tmp/assets

If you want to use a different directory, read below.

## Configuration
The plugin is configured with some constants. You can find these constants into 
`PLUGIN/config/constants.php`. To change the behavior of the plugin, you have 
to define these constants in your bootstrap, before the plugin is loaded.  
Example:

    define('ASSETS', TMP . 'custom_assets_dir');
    define('FORCE_ASSETS', true);
    Plugin::load('Assets', ['bootstrap' => true, 'routes' => true]);

## Versioning
For transparency and insight into our release cycle and to maintain backward 
compatibility, *Assets* will be maintained under the 
[Semantic Versioning guidelines](http://semver.org).
