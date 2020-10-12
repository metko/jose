<?php

namespace Jose\Core\Theme;



class Theme
{

   public function init() {

      // *******************
      // Remove the unused and useless function delivered by default with wordpress
      new \Jose\Core\Theme\CleanOutput();

      // *******************
      // Set theme supoort
      new \Jose\Core\Theme\ThemeSupport();

      // *******************
      // Set theme supoort
      new \Jose\Core\Theme\Permalink();

      // *******************
      // Set image size
      new \Jose\Core\Theme\ImageSize();

   }



   

   
}
