<?php

namespace CEHD\App;

trait SlimView
{
    public function configureTwigForSlim(
        \Slim\Container $container,
        array $app_config,
        array $filter_method_names,
        array $nav_items,
        bool $autoescape,
        bool $autoreload,
        bool $debug,
        string $inline_styles,
        string $inline_svg_styles,
        string $base_path
        )
    {
        $view = new \Slim\Views\Twig(__DIR__ . "/../../../templates", [
            "cache" => __DIR__ . "/../../cache",
            "auto_reload" => $autoreload, // this setting forces the templates to recompile if they are modified
            "debug" => $debug,
            "autoescape" => $autoescape,
        ]);

        $twig = $view->getEnvironment();

        $twig->addGlobal('config', $app_config);
        $twig->addGlobal('nav_items', $nav_items);
        $twig->addGlobal('inline_styles', $inline_styles);
        $twig->addGlobal('inline_svg_styles', $inline_svg_styles);

        foreach ($filter_method_names as $filter_method) {
            // @xxx Will change in version 2 of Twig
            $filter = new \Twig_SimpleFilter($filter_method, [$this, $filter_method]);
            $twig->addFilter($filter);
        }

        $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $base_path));

        return $view;
    }
}
