<?php

require_once __DIR__ . '/../cehd/app/Config/Config.php';

use PHPUnit\Framework\TestCase;


class ConfigTest extends TestCase
{
    protected $appConfigFilePath;

    protected $configObj;

    protected function setUp()
    {
        $_SERVER['SERVER_NAME'] = "scotch";

        $this->appConfigFilePath = __DIR__ . '/fixtures/config/app-config.yml';

        $this->configObj = new \CEHD\App\Config\Config;
    }

    public function testLoadYaml()
    {
        $result = $this->configObj->load([$this->appConfigFilePath]);
        $this->assertTrue(is_array($result));
    }

    public function testLoadMultipleYaml()
    {
        $site_config = __DIR__ . '/fixtures/config/site-config.yml';
        $configs = [
            $this->appConfigFilePath,
            $site_config,
        ];

        $result = $this->configObj->load($configs);
        $this->assertArrayHasKey('dept_name', $result);
        $this->assertEquals($result['dept_name'], 'Test Department');
    }

    public function testConfigHasEnvironment()
    {
        $result = $this->configObj->load([$this->appConfigFilePath]);
        $this->assertArrayHasKey('environment', $result);
    }

    public function testConfigEnvironmentIsDevelopment()
    {
        $result = $this->configObj->load([$this->appConfigFilePath]);
        $this->assertEquals($result['environment'], 'development');
    }

    public function testConfigEnvironmentIsNotProd()
    {
        $result = $this->configObj->load([$this->appConfigFilePath]);
        $this->assertFalse($result['is_prod']);
    }

    public function testConfigEnvironmentIsTestOnDrydock()
    {
        $_SERVER['SERVER_NAME'] = "drydock.cehd.umn.edu";
        $result = $this->configObj->load([$this->appConfigFilePath]);
        $this->assertFalse($result['is_prod']);
        $this->assertEquals($result['environment'], 'test');
    }

    public function testConfigCorrectlySetsDebug()
    {
        $result = $this->configObj->load([$this->appConfigFilePath]);
        $this->assertTrue($result['debug']);
    }
}
