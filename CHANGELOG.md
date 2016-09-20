# 1.x branch
## 1.1 branch
### 1.1.0
* the `Asset` utility has been renamed as `AssetsCreator` and now it uses the
    temporary directory (`APP/tmp/assets/`);
* added the `AssetsController` with the `asset()` method, which takes care of
    sending assets;
* added tests for `AssetsCreator` and `AssetHelper` classes.

## 1.0 branch
### 1.0.2
* fixed code for CakePHP Code Sniffer.

### 1.0.1
* improved code;
* executables are set up and checked in the bootstrap.