<?php

namespace Jose\Core;

use Jose\Core\Exceptions\ClassNotFoundException;
use Jose\Core\Exceptions\FunctionDoesntExist;
use Jose\Utils\Config;
use Timber\Menu;

class Context {

    public $config = true;
     // set the default context
    // it will be merged with the context in app/context.php
    public function init() {


        add_filter( 'timber/context', function($context) {
           
            $context['menu'] = new Menu('main_menu');
            
            $data['env'] = getenv('WP_ENV');

            // if breadcrumb
            $breadcrumb = Config::getInstance()->get('breadcrumbs');
            $output = '';
            if($breadcrumb) {

                if(array_key_exists('use_yoast_seo', $breadcrumb) && $breadcrumb['use_yoast_seo']){

                    if( ! function_exists("yoast_breadcrumb")) {
                        throw new FunctionDoesntExist('yoast_breadcrumb');
                    }

                    ob_start();
                    yoast_breadcrumb( $breadcrumb['yoast']['start'], $breadcrumb['yoast']['end'] );

                    $output = ob_get_clean();

                } else if (array_key_exists('custom_class', $breadcrumb) && $breadcrumb['custom_class']) {

                    if( ! class_exists($breadcrumb['custom_class'])) {
                        throw new ClassNotFoundException($breadcrumb['custom_class']);
                    }

                    $output = new $breadcrumb['custom_class']();


                }
            }
        
            // end

            // get data from app/context.php
            return array_merge($context, Config::getInstance()->getContext()) ;

        } );
        // if yoast ....
      
    }
}