<?php
namespace Jose\Core\Theme;

use Jose\Core\Context;
use Jose\Utils\Config;
use Timber\Menu;
use Timber\Timber;

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

      $menus = Config::getInstance()->get('menus_slot');
      $allMenus = [];

      foreach($menus as $menu_name => $menu_description) {
         register_nav_menu($menu_name, __($menu_description, "jose"));
         $allMenus[$menu_name] =  new \Timber\Menu($menu_name);
      }

      Context::getInstance()->pass('menus', $allMenus );

   }

}





