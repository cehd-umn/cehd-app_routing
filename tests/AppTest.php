<?php

require_once __DIR__ . '/../cehd/app/App/App.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// use Klein;

class AppTest extends TestCase
{
    private $appInstance;

    protected function setUp()
    {
        $appConfigFilePath = __DIR__ . '/fixtures/config/app-config.yml';
        $this->appInstance = new \CEHD\App\App\App([
            "configs" => [
                $appConfigFilePath,
            ],
        ]);
    }

    public function testCreation()
    {
        $this->assertInstanceOf(Klein\Klein::class, $this->appInstance->router);
        $this->assertTrue($this->appInstance->config['debug']);
    }
}
