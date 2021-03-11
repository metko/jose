<?php

namespace Jose;

use Jose\Core\Exceptions\ManifestAssetsNotFound;
use Jose\Utils\Config;
use Jose\Utils\Finder;

class Assets {

    public static $instance = null;    

    public $manifest = null;
        
    /**
     * init
     *
     * @return void
     */
    public function init() {
        $this->config = Config::getInstance()->get("assets");

        if($this->config['manifest']) {
            if(Finder::getInstance()->file_exists(
                    get_template_directory() . '/'.$this->config['dist_folder'].'/'.$this->config['manifest'])) {
                $this->manifest = json_decode(file_get_contents(get_template_directory() . '/'.$this->config['dist_folder'].'/'.$this->config['manifest']), true);
            }else {
                throw new ManifestAssetsNotFound();
            }
        }

        $this->registerMainScripts();

    }



    
    /**
     * loadDefaultScript
     *
     * @param  mixed $type
     * @return void
     */
    public function loadDefaultScript($type) {
        // get main css file name
        if($this->config) {

            foreach($this->config as $key => $assets) {

                // if key == css || js
               if($key == $type) {
                   //      css|js      path
                   foreach($assets as $asset) {
                       $this->$type($asset);
                   }
                   
               }
               
            } 
        }else {
            $this->$type("app.".$type);
        }
    }
   
    /**
     * registerMainScripts
     *
     * @return void
     */
    public function registerMainScripts() {
        
        // $this->js("runtime.js");  
        $this->loadDefaultScript("js");  
        $this->loadDefaultScript("css");  
    }

       
    /**
     * css
     *
     * @param  mixed $file
     * @param  mixed $groupe
     * @return void
     */
    public function css($file, $groupe = null) {
        if(!$groupe) {
            $groupe = $this->getGroup($file);
        }

        add_action('wp_enqueue_scripts', function() use($groupe, $file){
            wp_register_style($groupe,  $this->assetPath($file), '1.0.0', true);
            wp_enqueue_style($groupe);
        });


    }
 
    /**
     * js
     *
     * @param  mixed $file
     * @param  mixed $groupe
     * @return void
     */
    public function js($file, $groupe = null) {

        if(!$groupe) {
            $groupe = $this->getGroup($file);
        }

        add_action('wp_enqueue_scripts', function() use($groupe, $file){
            wp_register_script($groupe, $this->assetPath($file), [], '1.0.0', true);
            wp_enqueue_script($groupe);
        });

    }

    /**
     *
     * @param $key
     * @return string
     */
    public function assetPath($key): string
    {
        if ($this->manifest && array_key_exists($key, $this->manifest)) {
            return get_stylesheet_directory_uri() . '/' . $this->config['dist_folder'] . $this->manifest[$key];
        } else {
            return get_stylesheet_directory_uri() . '/' . $this->config['dist_folder'] . '/' . $key;
        }
    }
     
    /**


    /**
     * For twig media path
     * @param $key
     * @return string
     */
    public function img_url($key): string
    {
        if( $this->manifest ) {
            return get_stylesheet_directory_uri() . '/' . $this->config['dist_folder'] .'/images/' .$key;
        } else {
            return get_stylesheet_directory_uri() . '/' . $this->config['dist_folder'] . '/' . $key;
        }
    }

    

    /**
     * getGroup
     *
     * @param  mixed $file
     * @return void
     */
    public function getGroup($file) {

        $filename = explode('.', $file);
        $group =  $filename[0]."_style";
        
        return $group;
    }

    

     /**
     * getInstance
     *
     * @return 
     */
    public static function getInstance() {

        if(! self::$instance) {
            self::$instance = new Assets();
        }
        return self::$instance;
    } 

}