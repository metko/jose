<?php
namespace Jose\Core\Theme;

class ImageSize {


   public $imageSize = [
      ["avatar", 250, 250],
      ["hero", 1200, 400],
   ];


   public function __construct () {
      // Register a new image size.
      foreach ($this->imageSize as $image) : 
         add_image_size($image[0], $image[1], $image[2], true);
      endforeach;
     
   }

}





