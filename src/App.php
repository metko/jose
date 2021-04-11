<?php

namespace Jose;

use Jose\Exception\AppAlreadyRunException;
use Jose\Exception\NotFoundException;


class App
{

    /**
     * @var App
     */
    private static $instance;
    /**
     * @var Config
     */
    private $config;

    private $hasRun = false;

    public function __construct()
    {
        // autoload files on hooks
        // dd(jose('config')->get('hooks'));
    }

    public function run($config = null)
    {
        if ($this->hasRun) {
            throw new AppAlreadyRunException('App already run');
        }
        $this->hasRun = true;

        if ($config) {
            jose('config')->set($config);
        }

        $this->autoloadFiles();
        /* TODO 
        * - Setup the auto render view  // create the view container
        * - Setup the 
        */
    }
    

    private function autoloadFiles () 
    {
        // Check for hooks files
        if (array_key_exists('hooks',  jose('config')->get())) {

            foreach (jose('config')->get('hooks') as $hook => $file) {

                add_action($hook, function () use ($file) {
                    if(is_string($file)) {
                        
                        if (jose('file')->exists(ROOT_APP . $file)) {
                            require_once(ROOT_APP . $file);
                        } else {
                            throw new NotFoundException('Hook file ' . $file . ' not found');
                        }

                    } elseif( is_array($file)) {

                        forEach($file as $f) {

                            if (is_string($f) && jose('file')->exists(ROOT_APP . $f)) {
                                require_once(ROOT_APP . $f);
                            } else {
                                throw new NotFoundException('Hook file ' . $f . ' not found');
                            }
                        }
                    }
                    
                });
            }

        }
    }
}
