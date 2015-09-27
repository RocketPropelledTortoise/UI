<?php

namespace Rocket\UI\Script\Support\Middleware;

use Rocket\UI\Script\Support\Laravel5\Facade as JS;
use Symfony\Component\HttpFoundation\Request;

class ScriptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        if (!$response->headers->has('content-type') ||
            strpos($response->headers->get('content-type'), 'html') !== false) {
            $response->setContent($this->insertScript($response->getContent()));
        }

        return $response;
    }

    protected function insertScript($content)
    {
        $pos = strripos($content, '</body>');
        if ($pos !== false) {
            return substr($content, 0, $pos) . JS::render() . substr($content, $pos);
        }

        return $content . JS::render();
    }
}
