# Assets
*Assets* is a CakePHP plugin to allows you to generate assets.

## Installation
*Assets* uses [clean-css](https://github.com/jakubpawlowicz/clean-css) and [UglifyJS 2](https://github.com/mishoo/UglifyJS2)
to create assets. Before you start, you have to install them by using [Node.js](https://nodejs.org).

Then, you can install the plugin via composer:

    $ composer require --prefer-dist mirko-pagliai/assets
    
Then, edit `APP/config/bootstrap.php` to load the plugin:

    Plugin::load('Assets', ['bootstrap' => TRUE]);
    
By default the plugin uses the `APP/webroot/assets` directory to save the assets files.  
So you have to create the directory and make it writable:

    $ mkdir webroot/assets && chmod 775 webroot/assets

You can change this directory by defining `ASSETS` and `ASSETS_WWW` constants until the plugin is loaded. For example:

    define('ASSETS', WWW_ROOT.'custom_assets');
	define('ASSETS_WWW', '/custom_assets');
    Plugin::load('Assets', ['bootstrap' => TRUE]);

By default assets will be used only if debugging is off. If you want that assets are always used, even when the debugging 
is on, define `FORCE_ASSETS` the constant until the plugin is loaded. For example:

    define('FORCE_ASSETS', TRUE);
    Plugin::load('Assets', ['bootstrap' => TRUE]);