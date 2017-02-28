<?php

require_once __DIR__ . '/../cehd/app/App/App.php';
require_once __DIR__ . '/../cehd/app/helpers/Helper.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private $appInstance;

    protected function setUp()
    {
        $appConfigFilePath = __DIR__ . '/fixtures/config/app-config.yml';
        $navFilePath =       __DIR__ . '/fixtures/nav.yml';
        $routesFilePath =    __DIR__ . '/fixtures/routes.yml';
        $this->appInstance = new \CEHD\App\App\BaseApp([
            "configs" => [
                $appConfigFilePath,
            ],
            "nav" => $navFilePath,
            "routes" => $routesFilePath,
        ]);
    }

    public function testCreation()
    {
        // the router is an instance of Klein
        $this->assertInstanceOf(Klein\Klein::class, $this->appInstance->router);

        // template is an instance of Twig
        $this->assertInstanceOf(\Twig_Environment::class, $this->appInstance->template);

        // helper is an instance of \CEHD\Helpers\Helper
        $this->assertInstanceOf(\CEHD\Helpers\Helper\Helper::class, $this->appInstance->helpers);

        // the config is properly set
        $this->assertTrue($this->appInstance->config['debug']);
        $this->assertEquals("/assets", $this->appInstance->config['asset_base']);

        // nav is set
        $this->assertTrue(is_array($this->appInstance->nav_items));
        $this->assertEquals(8 ,count($this->appInstance->nav_items));

        $names = [
            "Home",
            "Programs & Degrees",
            "Current Students",
            "People",
            "Research",
            "Giving",
            "Alumni",
            "About",
        ];

        // the nav has the expected structure
        foreach(range(0, 7) as $i)
        {
            $this->assertEquals($names[$i] ,$this->appInstance->nav_items[$i]["name"]);
        }
    }

    public function testAlternateConfig()
    {
        $appConfigFilePath = __DIR__ . '/fixtures/config/app-config.yml';
        $yaConfigFilePath  = __DIR__ . '/fixtures/config/ya-config.yml';
        $navFilePath       = __DIR__ . '/fixtures/nav.yml';
        $routesFilePath    = __DIR__ . '/fixtures/routes.yml';
        $this->appInstance = new \CEHD\App\App\BaseApp([
            "configs" => [
                $appConfigFilePath,
                $yaConfigFilePath,
            ],
            "nav" => $navFilePath,
            "routes" => $routesFilePath,
        ]);

        $this->assertEquals("/new/base/assets", $this->appInstance->config['asset_base']);
    }
}
