<?php

namespace Jose\Core\Theme;

class ThemeSupport
{

   public function __construct()
   {
      $this->setThemeSupport();
   }

   /**
    * Add support featuree for the theme
    *
    * @param $sizes
    *
    * @return array
    */
   public function setThemeSupport()
   {

      // Enables Post Formats support for a theme.
      add_theme_support('post-formats', [
         'aside',   // Typically styled without a title.
         'audio',   // An audio file or playlist.
         'chat',    // A chat transcript
         'gallery', // A gallery of images.
         'image',   // A single image.
         'link',    // A link to another site.
         'quote',   // A quotation.
         'status',  // A short status update, similar to a Twitter status update.
         'video',   // A single video or video playlist.
      ]);

      // Enables Post Thumbnails support for a theme
      add_theme_support('post-thumbnails');

      // Adds RSS feed links to HTML <head>
      add_theme_support('automatic-feed-links');

      // Allows the use of HTML5 markup for the listen options
      add_theme_support('html5', [
         'caption',
         'comment-form',
         'comment-list',
         'gallery',
         'search-form',
      ]);
   }
}

// SCAN ROOTS.IO base DIRECTORIES and twig files. Twig files that you want to scan should start with {# <?php #}
add_action('wp_ajax_wpml_get_files_to_scan', function () {
   if ($_POST['theme']=='bedrock') {

      $result = array();
      $folders = array(dirname($_SERVER["DOCUMENT_ROOT"]).'/app', dirname($_SERVER["DOCUMENT_ROOT"]).'/src/views');

		if ( $folders ) {
         $file_type = array( 'php', 'twig' );
         
         $files_found_chunks = array();

			foreach ( $folders as $folder ) {
            
            $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder), \RecursiveIteratorIterator::SELF_FIRST);
         
            foreach($objects as $name => $object){
               if(!$object->isDir() && $object->isFile() && in_array($object->getExtension(), array('php', 'twig'))):
                  $files_found_chunks[] = $name;
               endif;
            }
			}
			$files = $files_found_chunks;
			$result = array(
				'files' => $files,
				'no_files_message' => __( 'Files already scanned.', 'wpml-string-translation' ),
			);
		}

      wp_send_json_success($result);

   }
}, 0);
