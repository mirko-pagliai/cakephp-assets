# Assets
*Assets* is a CakePHP plugin to allows you to generate assets.  
It uses [matthiasmullie/minify](https://github.com/matthiasmullie/minify) and
provides a convenient helper to generate and link assets files.

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
The plugin uses some configuration parameters.

You must set the configuration after you've loaded the plugin, so the default
configuration will be overwritten. For example, you can do this at the bottom 
of the file `APP/config/app.php` of your application.

### Configuration values

    Configure::write('Assets.force', false);

Setting `Assets.force` to `true`, the assets will be used even if debugging is 
enabled.

    Configure::write('Assets.target', TMP . 'assets');

Setting `Assets.target`, you can use another directory where the plugin will 
generate the assets.

## How to use
You have to use only the `AssetHelper`. This helper provides `css()` and
`script()` methods, similar to the methods provided by the `HtmlHelper`.

The syntax is the same, you just have to change the name helper. Example for
`AssetHelper::css()`.

    echo $this->Asset->css(['one.css', 'two.css']);

This will combine and compress `one.css` and `two.css` files, creating a unique
asset file, and will create a link element for CSS stylesheets, as does the 
method provided by the `HtmlHelper`.

The same also applies to the `AssetHelper::script()` method.

## Versioning
For transparency and insight into our release cycle and to maintain backward 
compatibility, *Assets* will be maintained under the 
[Semantic Versioning guidelines](http://semver.org).
