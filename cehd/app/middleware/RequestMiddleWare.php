<?php

namespace CEHD\App;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class RequestMiddleWare
{
    private $_container;

    private $_redirects;

    public function __construct($_container)
    {
        $this->_container = $_container;
        $this->_redirects = $_container["redirects"];
        $this->_redirect_prepend = $_container["redirect_prepend"];
    }

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
            $path = strtolower($uri->getPath());

            // this will be null if there's no match
            $redirect = $this->_redirects->get($path);

            if ($redirect !== null) {
                $uri = $this->_redirect_prepend . (string)$redirect;
                return $response->withRedirect($uri, 301);
            } else if ($this->_pathContainsDefaultDotHtml($path)) {
                // Any old url that had default.html should just end in a slash
                $newpath = rtrim($path, "default.html");
                $uri = $uri->withPath($newpath);
                return $response->withRedirect((string)$uri, 301);
            } elseif ($this->_pathContainsDotHtml($path)) {
                // Any old url that had .html should replace .html with "/"
                $newpath = str_replace(".html", "/", $path);
                $uri = $uri->withPath($newpath);
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

    // @todo does this really need to be private?
    private function _pathContainsDotHtml($path)
    {
       return (substr($path, -5) == ".html") ;
    }
}
