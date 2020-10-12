<?php
namespace Jose\Core\Theme;

use Jose\Utils\Config;

class RegisterMenu {


  
   public function init () {
      add_action('after_setup_theme', [$this, 'register_menus' ]);
   }

   /**
    * Register a new image size options to the list of selectable sizes in the Media Library
    *
    * @param $sizes
    *
    * @return array
    */
   public function register_menus()
   {
      $menus = Config::getInstance()->get('menus');
      $local_key = Config::getInstance()->get('local_key');

      foreach($menus as $menu_name => $menu_description) {
         register_nav_menu($menu_name, __($menu_description, $local_key));
      }
     
   }

}





