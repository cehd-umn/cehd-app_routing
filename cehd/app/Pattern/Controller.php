<?php
namespace CEHD\App\Controller\Pattern;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/App/App.php';
require_once __DIR__ . '/../../../cehd/app/traits/SlimRequest.php';

class PatternController extends \CEHD\App\App\BaseApp
{
    use \CEHD\App\SlimRequest;

    protected $_view;

    protected $_categories;

    protected $_patternCards;



    // @todo use type hinting
    // @todo pass in three params instead of the whole container
    function __construct($container)
    {
        $this->_view = $container->get("view");
        $this->_patternCards = $container->get("patternCards");
        $this->_categories = $container->get("categories");
    }

    public function home($request, $response, $args)
    {
        $template_data = [
            "patterns" => $this->_patternCards,
            "categories" => $this->_categories,
        ];
        $this->_view->render($response, "patterns/index.html", $template_data);
        return $response;
    }

    public function patternPageHandler($request, $response, $args)
    {
        $route = $request->getAttribute('route');
        $name = $request->getUri()->getPath();
        $base_path = $request->getUri()->getBasePath();
        $pattern = $this->_patternCards["/" . $name];
        $template_data = [
            "pattern" => $pattern,
        ];

        if ($pattern->type === "include") {
            $this->_view->render($response, "patterns/pattern.html", $template_data);
        } elseif ($pattern->type === "template") {
            $this->_view->render($response, $pattern->template, $template_data);
        }
        return $response;
    }

    // public function errorPageHandler($code=404, $router)
    // {
    //     // @todo Create separate 404 template
    //     $html = $this->_renderTemplate('cehd/error/404.html', []);
    //     $router->response()->status($code);
    //     $router->response()->body($html);
    // }
}
