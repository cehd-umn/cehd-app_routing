<?php
namespace CEHD\App\App;

require_once __DIR__ . '/../cehd/app/App/App.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use UMN\CEHD\Dict;

class CEHDApp extends BaseApp
{
    public function __construct($yaml_file_paths)
    {
        parent::__construct($yaml_file_paths);
    }
}
