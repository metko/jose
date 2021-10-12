<?php
namespace Jose\Components;

use Jose\Exception\NotFoundException;
use Jose\Exception\AssetsKeyNotFoundException;

class Assets {

    public $manifest = null;
    public $dist_folder = null;

    /**
     * @var mixed
     */
    public function __construct () {
        $this->manifest = jose('config')->get('assets.manifest');

        $this->dist_folder = jose('config')->get('assets.dist_folder');

        if ($this->manifest) {
            if ( ! jose('file')->exists(get_template_directory()."/".$this->manifest)) {
                throw new NotFoundException('Manifest '.get_template_directory()."/".$this->manifest.' not found!');
            }
            $this->manifest = json_decode(file_get_contents(get_template_directory()."/".$this->manifest), true);
        }
    }

    public function get (String $path) {

        if ($this->manifest) {

            if ( ! array_key_exists($path, $this->manifest)) {
                throw new AssetsKeyNotFoundException('Assets key not found in manifest!');
            } 
            $path = $this->manifest[$path];
        } 

        if ($this->dist_folder) { 
            $path = get_template_directory() . '/'. $this->dist_folder . $path;
        } else {
            $path = get_template_directory() . '/' . $path;
        }
        if ( ! jose('file')->exists($path)) {
            throw new NotFoundException('Asset file '.$path.' not found!');
        }
    
        return $path;
    }

}