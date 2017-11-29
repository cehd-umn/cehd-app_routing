<?php

namespace CEHD\App;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class RequestMiddleWare
{
    /**
     * Middleware class to handle a blanket redirect cases
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // Only handle GET requests
        if ($request->getMethod() == 'GET') {
            $uri = $request->getUri();
            $path = $uri->getPath();

            if ($this->_pathContainsDefaultDotHtml($path)) {
                $newpath = rtrim($path, "default.html");
                $uri = $uri->withPath($newpath);
                return $response->withRedirect((string)$uri, 301);
            } elseif ($this->_pathHasNoTrailingSlash($path)) {
                // add a trailing slash
                $uri = $uri->withPath($path . "/");
                return $response->withRedirect((string)$uri, 301);
            }
        }

        return $next($request, $response);
    }

    // @todo does this really need to be private?
    private function _pathHasNoTrailingSlash($path)
    {
        return ($path != "/" && substr($path, -1) != "/");
    }

    // @todo does this really need to be private?
    private function _pathContainsDefaultDotHtml($path)
    {
       return (substr($path, -12) == "default.html") ;
    }
}
