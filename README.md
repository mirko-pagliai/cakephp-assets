# cakephp-assets plugin

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![CI](https://github.com/mirko-pagliai/cakephp-assets/actions/workflows/ci.yml/badge.svg)](https://github.com/mirko-pagliai/cakephp-assets/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/mirko-pagliai/cakephp-assets/branch/master/graph/badge.svg)](https://codecov.io/gh/mirko-pagliai/cakephp-assets)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/aff2a01112854ae6bd8d57e27f37213d)](https://www.codacy.com/gh/mirko-pagliai/cakephp-assets/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mirko-pagliai/cakephp-assets&amp;utm_campaign=Badge_Grade)
[![CodeFactor](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-assets/badge)](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-assets)

*cakephp-assets* is a CakePHP plugin to allows you to handle and generate assets.

It uses [matthiasmullie/minify](https://github.com/matthiasmullie/minify) and
provides a convenient helper that allows you to combine multiple asset files
into one single compressed file.

Did you like this plugin? Its development requires a lot of time for me.
Please consider the possibility of making [a donation](https://paypal.me/mirkopagliai):
even a coffee is enough! Thank you.

[![Make a donation](https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_carte.jpg)](https://paypal.me/mirkopagliai)

* [Installation](#installation)
  + [Installation on older CakePHP and PHP versions](#installation-on-older-cakephp-and-php-versions)
    - [For PHP 7.2 and CakePHP 4 or later](#for-php-72-and-cakephp-4-or-later)
    - [For PHP 5.6 and CakePHP 3 or later](#for-php-56-and-cakephp-3-or-later)
* [Configuration](#configuration)
  + [Configuration values](#configuration-values)
* [How to use](#how-to-use)
* [Versioning](#versioning)

***

## Installation
You can install the plugin via composer:

```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-assets
```

Then you have to load the plugin. For more information on how to load the plugin,
please refer to the [Cookbook](https://book.cakephp.org/4.0/en/plugins.html#loading-a-plugin).

Simply, you can execute the shell command to enable the plugin:
```bash
bin/cake plugin load Assets
```
This would update your application's bootstrap method.

By default, the plugin uses the `APP/tmp/assets` directory to save the
asset files. So you have to create the directory and make it writable:

```bash
$ mkdir tmp/assets && chmod 775 tmp/assets
```

If you want to use a different directory, read the [Configuration](#configuration) section.

### Installation on older CakePHP and PHP versions
Recent packages and the master branch require at least CakePHP 5.0 and PHP 8.1
and the current development of the code is based on these and later versions of
CakePHP and PHP.
However, there are still some branches compatible with previous versions of
CakePHP and PHP.

#### For PHP 7.2 and CakePHP 4 or later
The [cakephp4](//github.com/mirko-pagliai/cakephp-assets/tree/cakephp4) branch
requires at least PHP `>=7.2` and CakePHP `^4.0`.

In this case, you can install the package as well:
```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-assets:dev-cakephp4
```

Note that the `cakephp4` branch will no longer be updated as of January 5, 2024,
except for security patches, and it matches the
[1.5.13](//github.com/mirko-pagliai/cakephp-assets/releases/tag/1.5.13) version.

#### For PHP 5.6 and CakePHP 3 or later
The [cakephp3](https://github.com/mirko-pagliai/cakephp-assets/tree/cakephp3) branch
requires at least PHP 5.6 and CakePHP 3.

In this case, you can install the package as well:
```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-assets:dev-cakephp3
```

Note that the `cakephp3` branch will no longer be updated as of April 27, 2021,
except for security patches, and it matches the
[1.5.4](https://github.com/mirko-pagliai/cakephp-assets/releases/tag/1.5.4) version.

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

Refer to our [API](https://mirko-pagliai.github.io/cakephp-assets).

## Versioning
For transparency and insight into our release cycle and to maintain backward
compatibility, *cakephp-assets* will be maintained under the
[Semantic Versioning guidelines](http://semver.org).
