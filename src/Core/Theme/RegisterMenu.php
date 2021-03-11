<?php
namespace Jose\Core\Theme;

use Jose\Core\Context;
use Jose\Utils\Config;
use Timber\Menu;
use Timber\Timber;

class RegisterMenu {


   public static $menus = null;
  
   public function init () {
       $this->register_menus();
   }

   /**
    * Register menus
    **
    */
   public function register_menus()
   {

      $menus = Config::getInstance()->get('menus_slot');
      foreach($menus as $menu_name => $menu_description) {
         register_nav_menu($menu_name, __($menu_description, "jose"));
      }

   }

   public static function getMenus() {
       $menus = Config::getInstance()->get('menus_slot');


       $allMenus = [];
       foreach($menus as $menu_name => $menu_description) {
           $allMenus[$menu_name] = new \Timber\Menu($menu_name);
       }
       self::$menus = $allMenus;

       Context::getInstance()->pass("menus", self::$menus);
   }

}





