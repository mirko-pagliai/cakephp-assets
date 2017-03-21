# 1.x branch
## 1.1 branch
### 1.1.3
* added `AssetNotFoundException` class. This exception is thrown when you
    request an asset that is not available;
* methods that have been deprecated with CakePHP 3.4 have been replaced;
* updated for CakePHP 3.4.

### 1.1.2
* fixed bug. Urls no longer contain the type of asset, but only the filename.

### 1.1.1
* the `AssetCreator` class has been rewritten, using only objects and improving
    the code;
* you can configure the plugin both before and after it is loaded.

### 1.1.0
* it uses `matthiasmullie/minify` instead of `clean-css` and `UglifyJS`;
* the `Asset` utility has been renamed as `AssetsCreator` and now it uses the
    temporary directory (`APP/tmp/assets/`);
* added the `AssetsController` with the `asset()` method, which takes care of
    sending assets;
* added tests for `AssetsController`, `AssetsCreator` and `AssetHelper` classes.

## 1.0 branch
### 1.0.2
* fixed code for CakePHP Code Sniffer.

### 1.0.1
* improved code;
* executables are set up and checked in the bootstrap.