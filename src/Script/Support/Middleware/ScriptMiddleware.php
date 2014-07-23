<?php

namespace Rocket\UI\Script\Support\Middleware;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Rocket\UI\Script\Support\Laravel\ScriptFacade as JS;

class ScriptMiddleware implements HttpKernelInterface {

    protected $app;

    public function __construct(HttpKernelInterface $app, array $options = []) {
        $this->app = $app;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true) {
        $response = $this->app->handle($request, $type, $catch);

        if (!$response->headers->has('content-type') ||
            strpos($response->headers->get('content-type'), 'html') !== false) {
            $response->setContent($this->insertScript($response->getContent()));
        }

        return $response;
    }

    protected function insertScript($content) {
        $pos = strripos($content, '</body>');
        if ($pos !== false) {
            return substr($content, 0, $pos) . JS::render() . substr($content, $pos);
        }

        return $content . JS::render();
    }
}
