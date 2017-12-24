<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Router;
use Dingo\Api\Auth\Auth as Authentication;
use League\OAuth2\Server\Exception\AccessDeniedException;

class OAuthMiddleware
{
    private $auth;
    private $router;

    /**
     * Create a new auth middleware instance.
     *
     * @param \Dingo\Api\Routing\Router $router
     * @param \Dingo\Api\Auth\Auth      $auth
     */
    public function __construct(Router $router, Authentication $auth)
    {
        $this->router = $router;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                $request
     * @param Closure|\PHPHub\Http\Middleware\Closure $next
     * @param $type
     *
     * @return mixed
     *
     * @throws AccessDeniedException
     */
    public function handle($request, Closure $next, $type = null)
    {
        $route = $this->router->getCurrentRoute();

        if (! $this->auth->check(false)) {
            $this->auth->authenticate($route->getAuthenticationProviders());
        }

        return $next($request);
    }
}
