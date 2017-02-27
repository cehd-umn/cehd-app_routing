<?php
namespace CEHD\App\EdPsych;

require_once __DIR__ . '/../../../cehd/app/App/App.php';

class EdPsychApp extends \CEHD\App\App\BaseApp
{
    public $nav_open = false;

    // @todo move routes in public/edpsych/index.php here
    function __construct($file_paths)
    {
        parent::__construct($file_paths);

        // Check for main nav state
        if (array_key_exists('cehd-edpsych-navOpen', $_COOKIE)) {
            $this->nav_open = $_COOKIE['cehd-edpsych-navOpen'];
        }

        $this->template->addGlobal('cehd_edpsych_nav_open', $this->nav_open);
    }
}
