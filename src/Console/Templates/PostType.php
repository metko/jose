<?php

namespace App\Config\PostType;

class {!!SINGULAR_NAME!!}PostType {

   public function __construct() {
         $this->register{!!SINGULAR_NAME!!}PostType();
   }

   public function register{!!SINGULAR_NAME!!}PostType() {
      $labels = array(
         'name'               => _x('{!!PLURAL_NAME!!}', 'Post type general name', 'twn'),
         'singular_name'      => _x('{!!SINGULAR_NAME!!}', 'Post type singular name', 'twn'),
         'menu_name'          => _x('{!!PLURAL_NAME!!}', 'admin menu', 'twn'),
         'name_admin_bar'     => _x('{!!PLURAL_NAME!!}', 'add new on admin bar', 'twn'),
         'add_new'            => _x('Add {!!SINGULAR_LOWER_NAME!!}', '${!!SINGULAR_NAME!!}', 'twn'),
         'add_new_item'       => __('Add new {!!SINGULAR_LOWER_NAME!!}', 'twn'),
         'new_item'           => __('New {!!SINGULAR_LOWER_NAME!!}', 'twn'),
         'edit_item'          => __('Edit {!!SINGULAR_LOWER_NAME!!}', 'twn'),
         'view_item'          => __('View {!!SINGULAR_LOWER_NAME!!}', 'twn'),
         'all_items'          => __('All {!!PLURAL_LOWER_NAME!!}', 'twn'),
         'search_items'       => __('Search {!!PLURAL_LOWER_NAME!!}', 'twn'),
         'parent_item_colon'  => __('Parent {!!PLURAL_LOWER_NAME!!}:', 'twn'),
         'not_found'          => __('No {!!PLURAL_LOWER_NAME!!} found.', 'twn'),
         'not_found_in_trash' => __('No {!!PLURAL_LOWER_NAME!!} found in Trash.', 'twn')
      );

      $args = array(
         'labels'             => $labels,
         'description'        => __('{!!PLURAL_NAME!!} post.', 'twn'),
         'public'             => true,
         'publicly_queryable' => true,
         'show_ui'            => true,
         'show_in_menu'       => true,
         'query_var'          => true,
         'rewrite'            => array('slug' => '{!!PLURAL_LOWER_NAME!!}', 'with_front' => true),
         'has_archive'        => true,
         'hierarchical'       => false,
         'menu_position'      => null,
         'show_in_rest'       => true,
         'menu_icon'          => 'dashicons-smiley',
         'supports'           => array('title', 'page-attributes', 'thumbnail','revisions', 'editor', 'excerpt')
      );

      register_post_type('{!!SINGULAR_LOWER_NAME!!}', $args);

      //add_action( 'pre_get_posts', [$this, 'set_max_posts_page'] );
      
   }

   public function set_max_posts_page( $query ) {

      if ( $query->is_main_query() && ! is_admin() && is_post_type_archive( '{!!SINGULAR_LOWER_NAME!!}' ) ) {
          $query->set( 'posts_per_page', '2' );
      }
  }

}