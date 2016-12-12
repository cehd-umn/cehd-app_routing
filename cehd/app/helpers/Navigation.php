<?php
namespace CEHD\Helpers\Navigation;

require_once __DIR__ . '/../../../vendor/autoload.php';

class Navigation {
    protected $nav_items = null;

    public function set_nav($file_path) {
        $yaml = new \Symfony\Component\Yaml\Yaml;
        $this->nav_items = $yaml::parse(file_get_contents($file_path));
    }

    public function get_nav() {
        return $this->nav_items;
    }
}
