<?php

namespace Jose\Components;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Logger 
{
    public $log = null;

    public function __construct () {
        $this->log = new Monolog('JOSE');
        $this->log->pushHandler(new StreamHandler(ROOT_APP . '/storage/debug.log' , Monolog::DEBUG));
        $this->log->pushHandler(new FirePHPHandler());
    }

    public function warning ($msg) {
        $this->log->warning($msg);
    }

    public function info ($msg) {
        $this->log->info($msg);
    }

    public function error ($msg) {
        $this->log->error($msg);
    }

    public function notice ($msg) {
        $this->log->notice($msg);
    }

}