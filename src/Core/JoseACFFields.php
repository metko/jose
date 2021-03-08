<?php

namespace Jose\Core;

use DirectoryIterator;
use Jose\Core\Exceptions\FolderDoesntExistException;
use Jose\Utils\Config;
use Jose\Utils\Finder;
use Timber\Timber;

class JoseACFFields {


    /**
     * @var string
     */
    private $acf_fields_path;

    public function __construct() {

        if(! $this->acf_fields_path = Config::getInstance()->get("acf_fields_path") ) {
            $this->acf_fields_path = null;
        }
    }

    public function init() {
        if( ! $this->acf_fields_path) return;

        if ( class_exists('ACF')) {
//            add_action( 'acf/init', [$this, 'jose_acf_json_init'], 1, 0 );
//            add_filter('acf/settings/load_json', [$this, 'acf_json_load'], 20);
            $this->jose_acf_php_init();
        }

    }

    public function jose_acf_php_init() {

        if(!Finder::getInstance()->file_exists(ROOT.$this->acf_fields_path)) {
            throw new FolderDoesntExistException(ROOT.$this->acf_fields_path);
        }

        foreach ( Finder::getInstance()->getFiles(ROOT.$this->acf_fields_path) as $file ) {
            //dump($file);
            // Get file path
            $file_path = $file->getPathname();
            require_once($file_path);
        }
    }

    public function acf_json_save( $path ) {
        // update path
        $path = ROOT . $this->acf_fields_path;
        // return
        return $path;

    }
    public function acf_json_load( $paths ) {
        // remove original path
        unset($paths[0]);

        // append path
        $paths[] = ROOT . $this->acf_fields_path;

        // return
        return $paths;

    }
    public function jose_acf_json_init() {
        add_filter('acf/settings/save_json', [$this, 'acf_json_save']);

    }

}
