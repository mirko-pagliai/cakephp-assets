<?php

namespace App;

use Assets\Plugin as Assets;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    public function bootstrap()
    {
        $this->addPlugin(Assets::class);
    }

    public function middleware($middleware)
    {
        return $middleware->add(new RoutingMiddleware($this));
    }
}
