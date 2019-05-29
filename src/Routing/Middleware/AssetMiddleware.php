<?php
/**
 * This file is part of cakephp-assets.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/cakephp-assets
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 * @since       1.3.0
 */
namespace Assets\Routing\Middleware;

use Assets\Http\Exception\AssetNotFoundException;
use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Handles serving assets
 */
class AssetMiddleware
{
    /**
     * Serves assets if the request matches one
     * @param \Psr\Http\Message\ServerRequestInterface $request The request
     * @param \Psr\Http\Message\SResponseInterface $response The response
     * @param callable $next Callback to invoke the next middleware
     * @return \Psr\Http\Message\SResponseInterface A response
     * @throws \Assets\Http\Exception\AssetNotFoundException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $file = Configure::read('Assets.target') . DS . $request->getParam('filename');
        is_readable_or_fail($file, __d('assets', 'File `{0}` doesn\'t exist', $file), AssetNotFoundException::class);

        $response = $response->withModified(filemtime($file));
        if ($response->checkNotModified($request)) {
            return $response;
        }

        return $response->withFile($file)->withType(pathinfo($file, PATHINFO_EXTENSION));
    }
}
