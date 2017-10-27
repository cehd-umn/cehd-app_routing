<?php

namespace CEHD\App;

trait SlimView
{
    public function setUpTwig(
        array $app_config,
        array $filter_method_names,
        bool $autoescape,
        bool $autoreload,
        bool $debug
        )
    {
        $view = new \Slim\Views\Twig(__DIR__ . "/../../../templates", [
            "cache" => __DIR__ . "/../../../cache",
            "auto_reload" => $autoreload, // this setting forces the templates to recompile if they are modified
            "debug" => $debug,
            "autoescape" => $autoescape,
        ]);

        $twig = $view->getEnvironment();

        $twig->addGlobal('config', $app_config);

        foreach ($filter_method_names as $filter_method) {
            // @xxx Will change in version 2 of Twig
            $filter = new \Twig_SimpleFilter($filter_method, [$this, $filter_method]);
            $twig->addFilter($filter);
        }

        return $view;
    }

    public function configureTwigForSlim(\Slim\Container $container, $view, string $base_path)
    {
        $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $base_path));
        return $view;
    }
}
