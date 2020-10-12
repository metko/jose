<?php

namespace Jose\Core;

use Jose\Utils\Config;
use Timber\Timber;

class Views {

    public function init() {
        $this->setTimberViews();
    }

    public function setTimberViews() {
        $views = Config::getInstance()->get('views_path');
        //dd($views);
        Timber::$locations = ROOT.$views[0];
    }

}