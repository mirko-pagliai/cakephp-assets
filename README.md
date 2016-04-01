# Assets
*Assets* is a CakePHP plugin to allows you to generate assets.

## Installation
*Assets* uses [clean-css](https://github.com/jakubpawlowicz/clean-css) and 
[UglifyJS 2](https://github.com/mishoo/UglifyJS2). Before you start, you have 
to install them by using [Node.js](https://nodejs.org).  
Example:
	
	$ sudo npm install clean-css -g
	$ sudo npm install uglify-js -g

Then, you can install the plugin via composer:

    $ composer require --prefer-dist mirko-pagliai/assets
    
You have to edit `APP/config/bootstrap.php` to load the plugin:

    Plugin::load('Assets', ['bootstrap' => TRUE]);
    
By default the plugin uses the `APP/webroot/assets` directory to save the 
asset files. So you have to create the directory and make it writable:

    $ mkdir webroot/assets && chmod 775 webroot/assets

If you want to use a different directory, read below.

## Configuration
The plugin is configured with some constants. You can find these constants into 
`PLUGIN/config/constants.php`. To change the behavior of the plugin, you have 
to define these constants in your bootstrap, before the plugin is loaded.  
Example:

    define('ASSETS', WWW_ROOT.'custom_assets');
	define('ASSETS_WWW', '/custom_assets');
    define('FORCE_ASSETS', TRUE);
    Plugin::load('Assets', ['bootstrap' => TRUE]);

Note that the plugin sets the executables for *clean-css* and *UglifyJS 2* 
using the Unix `which` command.  
If you want to set other executables or if you cannot use the `which` command, 
you have to define these constants in your bootstrap, before the plugin is 
loaded.  
Example:

	define('CLEANCSS_BIN', '/full/path/to/cleancss');
	define('UGLIFYJS_BIN', '/full/path/to/uglifyjs'));
    Plugin::load('Assets', ['bootstrap' => TRUE]);