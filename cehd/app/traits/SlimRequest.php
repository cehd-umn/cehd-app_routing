<?php

namespace CEHD\App;

trait SlimRequest
{
    public function getPathFromRequest($request, $prepend="/")
    {
        $path = $prepend . $request->getUri()->getPath();

        if ($path === "//") {
            $path = "/";
        }
        return $path;
    }
}
