<?php

namespace Jose\Core\Blocks;

use ErrorException;
use Jose\Utils\Config;
use Jose\Utils\Finder;

class ACFBlocks {
    
    // Block path for files
    public $blocks_path = null;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        // get Block path
        if(!empty(Config::getInstance()->get('acf_block_path'))) {
            $this->blocks_path = Config::getInstance()->get('acf_block_path');
        }
    }
    
    /**
     * init
     *
     * @return void
     */
    public function init() {
        // if block path is null return
        if(! $this->blocks_path ) {
            return;
        }

        // If ! acf
        if ( ! function_exists( 'acf_register_block' ) ) {
            throw new ErrorException('You must use and activate acf pro to use ACFBlocks');
            return;
        }
        
        // Scan the path and init all class inside
        foreach ( Finder::getInstance()->getFiles(ROOT.$this->blocks_path) as $file ) {
                       
            // Get file path
            $file_path = $file->getRelativePathname();

            // Convert into a class namespace accessible
            $class_name = pathToNamespace($this->blocks_path) . explode('.', $file_path)[0];
            
            // Then register the post type$$
            new $class_name();
           
        }
    }

}
