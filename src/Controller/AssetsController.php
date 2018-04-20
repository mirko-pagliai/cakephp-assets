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
namespace Assets\Controller;

use Assets\Http\Exception\AssetNotFoundException;
use Cake\Controller\Controller;
use Cake\Core\Configure;

/**
 * Assets controller class
 */
class AssetsController extends Controller
{
    /**
     * Renders an asset
     * @param string $filename Asset filename
     * @return Cake\Network\Response|null
     * @throws AssetNotFoundException
     */
    public function asset($filename)
    {
        $file = Configure::read(ASSETS . '.target') . DS . $filename;

        if (!is_readable($file)) {
            throw new AssetNotFoundException(__d('assets', 'File `{0}` doesn\'t exist', $file));
        }

        $response = $this->response->withModified(filemtime($file));

        if ($response->checkNotModified($this->request)) {
            return $response;
        }

        return $response->withFile($file)->withType(pathinfo($file, PATHINFO_EXTENSION));
    }
}
