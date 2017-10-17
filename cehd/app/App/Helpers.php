<?php

namespace CEHD\App\Helpers;

require_once __DIR__ . '/../../../cehd/app/Base.php';
require_once __DIR__ . '/../../../cehd/app/traits/SlimView.php';
require_once __DIR__ . '/../../../cehd/app/traits/Environment.php';

class Helpers extends \CEHD\App\BaseClass
{
    use \CEHD\App\SlimView;
    use \CEHD\App\Environment;
}
