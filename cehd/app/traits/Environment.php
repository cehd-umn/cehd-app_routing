<?php

namespace CEHD\App;

trait Environment
{
    public function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function isProduction(string $server_name, string $prod_name_includes="www")
    {
        return strpos($server_name, $prod_name_includes) !== false;
    }

    public function isNotProduction(string $server_name, string $prod_name_includes="www")
    {
        return !$this->isProduction($server_name, $prod_name_includes);
    }
}
