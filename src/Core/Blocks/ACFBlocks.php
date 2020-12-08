<?php

namespace Jose\Core\Blocks;

use ErrorException;
use Jose\Core\PostClassMap;
use Jose\Utils\Config;
use Jose\Utils\Finder;
use Timber\Timber;

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
        if ( ! function_exists( 'acf_register_block' )  && ! function_exists( 'acf_maybe_get_POST') ) {
            throw new ErrorException('You must use and activate acf pro to use ACFBlocks');
            return;
        }
        
        if (  function_exists( 'acf_maybe_get_POST' ) ) {
            $post_id = intval( acf_maybe_get_POST( 'post_id' ) );
            $post = get_post($post_id);
        }else {
            global $post;
        }

        $global_post = null;
        
        if($post) {
            $classMap  = PostClassMap::getInstance()->classMap;
            if(array_key_exists($post->post_type, $classMap)) {
                $global_post = Timber::query_post($post_id, $classMap[$post->post_type]);
            } else {
                $global_post = Timber::query_post($post_id);
            }
        }
        
        // Scan the path and init all class inside
        foreach ( Finder::getInstance()->getFiles(ROOT.$this->blocks_path) as $file ) {
                       
            // Get file path
            $file_path = $file->getRelativePathname();

            // Convert into a class namespace accessible
            $class_name = pathToNamespace($this->blocks_path) . explode('.', $file_path)[0];
            
            // Then register the post type$$
            new $class_name($global_post);
           
        }
    }

}
