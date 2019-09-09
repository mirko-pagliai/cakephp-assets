# cakephp-assets plugin

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://travis-ci.org/mirko-pagliai/cakephp-assets.svg?branch=master)](https://travis-ci.org/mirko-pagliai/cakephp-assets)
[![codecov](https://codecov.io/gh/mirko-pagliai/cakephp-assets/branch/master/graph/badge.svg)](https://codecov.io/gh/mirko-pagliai/cakephp-assets)
[![Build status](https://ci.appveyor.com/api/projects/status/2ir3h63d1913cyhb?svg=true)](https://ci.appveyor.com/project/mirko-pagliai/cakephp-assets)
[![CodeFactor](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-assets/badge/develop)](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-assets/overview/develop)

*cakephp-assets* is a CakePHP plugin to allows you to handle and generate assets.

It uses [matthiasmullie/minify](https://github.com/matthiasmullie/minify) and
provides a convenient helper that allows you to combine multiple asset files
into one single compressed file.

Did you like this plugin? Its development requires a lot of time for me.
Please consider the possibility of making [a donation](//paypal.me/mirkopagliai):  
even a coffee is enough! Thank you.

[![Make a donation](https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_carte.jpg)](//paypal.me/mirkopagliai)

*   [Installation](#installation)
*   [Configuration](#configuration)
    * [Configuration values](#configuration-values)
*   [How to use](#how-to-use)
*   [Versioning](#versioning)

***

## Installation
You can install the plugin via composer:

```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-assets
```

**NOTE: the latest version available requires at least CakePHP 3.7**.

Instead, the [cakephp3.2](//github.com/mirko-pagliai/cakephp-assets/tree/cakephp3.2)
branch is compatible with all previous versions of CakePHP from version 3.2.4.  
This branch coincides with the 1.4 version of *cakephp-assets* and in any
case it will no longer receive new features but only bugfixes.

In this case, you can install the package as well:

```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-assets:dev-cakephp3.2
```

After installation, you have to edit `APP/config/bootstrap.php` to load the plugin:

```php
Plugin::load('Assets', ['bootstrap' => true, 'routes' => true]);
```

For more information on how to load the plugin, please refer to the
[Cookbook](http://book.cakephp.org/3.0/en/plugins.html#loading-a-plugin).

By default the plugin uses the `APP/tmp/assets` directory to save the
asset files. So you have to create the directory and make it writable:

```bash
$ mkdir tmp/assets && chmod 775 tmp/assets
```

If you want to use a different directory, read below.

## Configuration
The plugin uses some configuration parameters and you can set them using the
`\Cake\Core\Configure` class, **before** loading the plugin.

For example, you can do this at the bottom of the file `APP/config/app.php`
of your application.

### Configuration values

```php
Configure::write('Assets.force', false);
```

Setting `Assets.force` to `true`, the assets will be used even if debugging is
enabled.

```php
Configure::write('Assets.target', TMP . 'assets');
```

Setting `Assets.target`, you can use another directory where the plugin will
generate the assets.

## How to use
You have to use only the `AssetHelper`. This helper provides `css()` and
`script()` methods, similar to the methods provided by the `HtmlHelper`.

The syntax is the same, you just have to change the name helper. Example for
`AssetHelper::css()`.

```php
echo $this->Asset->css(['one.css', 'two.css']);
```

This will combine and compress `one.css` and `two.css` files, creating a unique
asset file, and will create a link element for CSS stylesheets, as does the
method provided by the `HtmlHelper`.

The same also applies to the `AssetHelper::script()` method.

Refer to our [API](//mirko-pagliai.github.io/cakephp-assets).

## Versioning
For transparency and insight into our release cycle and to maintain backward
compatibility, *cakephp-assets* will be maintained under the
[Semantic Versioning guidelines](http://semver.org).
