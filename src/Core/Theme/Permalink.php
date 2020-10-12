<?php

namespace Jose\Core\Theme;


class Permalink 
{

   public function __construct () {
      add_action('init', [$this, 'set_permalink' ]);
   }

   // set permalink
   public function set_permalink(){
      global $wp_rewrite;
      $wp_rewrite->set_permalink_structure('/%postname%/');
   }


}