<?php

namespace Jose\Core;

use ErrorException;
use Jose\Core\Exceptions\ConfigFileNotException;
use Jose\Jose;
use Jose\Utils\Config;
use Jose\Utils\Finder;

class Plugins {

   
        
    /**
     * config 
     *
     * @var undefined
     */
    public $config = null;

    /**
     * default_config for plugins options
     *
     * @var undefined
     */
    public $default_config = null;
    
    public function __construct() {

        // chercher un fichier de conf plugins
        $conf = Config::getInstance()->get('plugins_conf_path');

        if(isset($conf) && $conf) {
            
            if(!  Finder::getInstance()->file_exists(ROOT.$conf)) {
                throw new ConfigFileNotException(ROOT.$conf);
            }

           $this->config =Finder::getInstance()->require(ROOT.$conf);
           $this->default_config =Finder::getInstance()->require(ROOT_JOSE."config_default_plugins.php");
        }
        
    }
    
    /**
     * init
     *
     * @return void
     * Regarder si la conf existe et exécuter la fonction du même nom
     */
    public function init() {

        // appliquer les conf de plugins selectionné
        foreach($this->config as $k => $v) {

            // check if we haver a match with the accessible plugins conf
            if(\array_key_exists($k, $this->default_config)) {
                $this->$k($v);
            }

        }
    }
    
    /**
     * yoast_seo
     *
     * @param  mixed $value
     * @return void
     */
    public function yoast_seo($value): void
    {

        if ( ! \defined( 'WPSEO_VERSION' ) ) {
            throw new ErrorException('Plugin yoast seo is not activated');
        }
        
        $gen_conf = null;
        if($value === true) {
            $gen_conf = $this->default_config['yoast_seo'];
        }else if( is_array($value) && !empty($value)) {
            $gen_conf = $value;
        }

        if( $gen_conf ) {

            // add breadcrum generation
            if(!is_admin()) {
                add_action('template_redirect', function() use ($gen_conf){
                    if ( function_exists('yoast_breadcrumb') ) {
                        $b = $gen_conf['breadcrumb'];
                        $breadcrumb = yoast_breadcrumb( '<'.$b['tag'].' class="'.$b['class'].'">','</'.$b['tag'].'>', false);
                        Context::getInstance()->pass('breadcrumb', $breadcrumb);
                    }
                });
                           
            }

        }
    }

    public function wpml($value):void 
    {
        // dd('coucou');
        if(is_admin()) {
            if(array_key_exists("scan_dir", $value) ) {
            
                add_action('wp_ajax_wpml_get_files_to_scan', function () use($value) {
                 
                    $result = array();
                
                    if ( $value['scan_dir'] ) {
                        $file_type = array( 'php', 'twig' );
                        
                        $files_found_chunks = array();
                        $dir = array_merge($value['scan_dir'], [get_template_directory()]);
                        foreach (  $dir as $folder ) {
                        
                            $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder), \RecursiveIteratorIterator::SELF_FIRST);
                        
                            foreach($objects as $name => $object){
                                if(!$object->isDir() && $object->isFile() && in_array($object->getExtension(), $file_type )):
                                    $files_found_chunks[] = $name;
                                endif;
                            }
                        }
    
                        $files = $files_found_chunks;
                        $result = array(
                            'files' => $files,
                            'no_files_message' => __( 'Files already scanned.', 'wpml-string-translation' ),
                        );
                    }
                
                    wp_send_json_success($result);
                
                    
                }, 0);
            }
        }
         
    }

}
