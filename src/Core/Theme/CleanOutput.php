<?php

namespace Jose\Core\Theme;

use Jose\Core\Theme\NavWalker;

class CleanOutput {

    public function __construct() {
        $this->cleanOutput();
        $this->head_cleanup();
    }

    // *******************
    // All the filter and actions to remove what we want =)
    public function cleanOutput() {

  
         // *******************
         // CLEAN THEME
         add_action('style_loader_tag',  [$this, 'clean_style_tag']);
         //add_action('script_loader_tag', [$this, 'clean_script_tag']);
         add_action('body_class', [$this, 'body_class']);
         add_action('embed_oembed_html', [$this, 'embed_wrap']);
         add_action('get_avatar', [$this, 'remove_self_closing_tags']);
         add_action('comment_id_fields', [$this, 'remove_self_closing_tags']);
         add_action('post_thumbnail_html', [$this, 'remove_self_closing_tags']);
         add_action('get_bloginfo_rss', [$this, 'remove_default_description']);
         add_action('script_loader_src', [$this, 'remove_script_version']);
         add_action('style_loader_src', [$this, 'remove_script_version']);
         add_action('wp_enqueue_scripts', [$this, 'removeRegisterScript']);
         add_action('wp_enqueue_scripts', [$this, 'remove_block_library_css']);
         add_action('wp_enqueue_scripts', [$this, 'remove_block_library_css']);
         // END CLEAN THEME
         // *******************
  
         // *******************
         // REMOVE PINGBACK 
         add_action('wp_headers', [$this, 'filter_headers']);
         add_action('rewrite_rules_array', [$this, 'filter_rewrites']);
         add_action('bloginfo_url', [$this, 'kill_pingback_url']);
   
         add_action('xmlrpc_methods', [$this, 'filter_xmlrpc_method']);
         add_action('xmlrpc_call', [$this, 'kill_xmlrpc']);
         add_action('pre_ping', [$this, 'no_self_ping']);
   
         add_action('do_feed', [$this, 'itsme_disable_feed'], 1);
         add_action('do_feed_rdf', [$this, 'itsme_disable_feed'], 1);
         add_action('do_feed_rss', [$this, 'itsme_disable_feed'], 1);
         add_action('do_feed_rss2', [$this, 'itsme_disable_feed'], 1);
         add_action('do_feed_atom', [$this, 'itsme_disable_feed'], 1);
         add_action('do_feed_rss2_comments', [$this, 'itsme_disable_feed'], 1);
         add_action('do_feed_atom_comments', [$this, 'itsme_disable_feed'], 1);
   
         remove_action('wp_head', 'feed_links_extra', 3);
         remove_action('wp_head', 'feed_links', 2);
         // END REMOVE PINGBACK 
         // *******************
  
          // *******************
         // REMOVE REST API
         // remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
         // remove_action('template_redirect', 'rest_output_link_header', 11);
         // remove_action('wp_head', 'rest_output_link_wp_head', 10);
   
         add_filter('rest_authentication_errors', function ($result) {
            //return new \WP_Error('rest_forbidden', __('REST API forbidden.', 'soil'), ['status' => rest_authorization_required_code()]);
         });
         // END REMOVE REST API
         // *******************
         
         // *******************
         // CLEAN THE MENU WORDPRESS CLASS
         add_filter('wp_nav_menu_args', [$this, 'nav_menu_args']);
         // *******************

         // REDIRECT SEARCH QUERY ?s= to /search/{query}
         add_action('template_redirect', [$this, 'redirect']);
         add_filter('wpseo_json_ld_search_url', [$this, 'rewrite']);
   
         // *******************
         // Low YOAST
         add_filter( 'wpseo_metabox_prio', [$this, 'yoasttobottom']);
         // *******************
         /**
            * Remove the WordPress version from RSS feeds
            */
         add_filter('the_generator', '__return_false');
     }
  
     public function itsme_disable_feed()
     {
        wp_die(__('No feed available, please visit the homepage!'));
     }
  
     public function remove_block_library_css()
     {
         if(!is_admin()) {
             wp_dequeue_style('wp-block-library');
             wp_dequeue_style( 'wp-block-library-theme' );
             wp_dequeue_style( 'wc-block-style' ); // Re
         }
     }
  
     /**
      * Redirects search results from /?s=query to /search/query/, converts %20 to +
      *
      * @link http://txfx.net/wordpress-plugins/nice-search/
      *
      * You can enable/disable this feature in functions.php (or app/setup.php if you're using Sage):
      * add_theme_support('soil-nice-search');
      */
     public function redirect()
     {
        global $wp_rewrite;
        if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->get_search_permastruct()) {
           return;
        }
  
        $search_base = $wp_rewrite->search_base;
        if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false && strpos($_SERVER['REQUEST_URI'], '&') === false) {
           wp_redirect(get_search_link());
           exit();
        }
     }
  
     public function rewrite($url)
     {
        return str_replace('/?s=', '/search/', $url);
     }
  
     public  function no_self_ping(&$links)
     {
        $home = get_option('home');
        foreach ($links as $l => $link)
           if (0 === strpos($link, $home))
              unset($links[$l]);
     }
  
     /**
      * Clean up wp_nav_menu_args
      *
      * Remove the container
      * Remove the id="" on nav menu items
      */
     public function nav_menu_args($args = '')
     {   
       // dd($args);
        $nav_menu_args = [];
        $nav_menu_args['container'] = false;
        
        if (!$args['items_wrap']) {
           $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
        }
        
        if (!$args['walker']) {
         
           $nav_menu_args['walker'] = new NavWalker();
        }
  
        return array_merge($args, $nav_menu_args);
     }
  
     public function removeRegisterScript()
     {
        remove_action('wp_head', 'wp_print_scripts');
        remove_action('wp_head', 'wp_print_head_scripts', 9);
        remove_action('wp_head', 'wp_enqueue_scripts', 1);
     }
  
     /**
      * Disable XMLRPC call
      */
     public function kill_xmlrpc($action)
     {
        if ($action === 'pingback.ping') {
           wp_die('Pingbacks are not supported', 'Not Allowed!', ['response' => 403]);
        }
     }
  
     /**
      * Kill trackback rewrite rule
      */
     public function filter_rewrites($rules)
     {
        foreach ($rules as $rule => $rewrite) {
           if (preg_match('/trackback\/\?\$$/i', $rule)) {
              unset($rules[$rule]);
           }
        }
        return $rules;
     }
  
     /**
      * Kill bloginfo('pingback_url')
      */
     public function kill_pingback_url($output)
     {
        return "";
     }
  
     /**
      * Remove pingback header
      */
     public function filter_headers($headers)
     {
        if (isset($headers['X-Pingback'])) {
           unset($headers['X-Pingback']);
        }
        return $headers;
     }
  
     public function remove_script_version($src)
     {
        return $src ? esc_url(remove_query_arg('ver', $src)) : false;
     }
  
     /**
      * Disable pingback XMLRPC method
      */
     public function filter_xmlrpc_method($methods)
     {
        unset($methods['pingback.ping']);
        return $methods;
     }
  
     /**
      * Wrap embedded media as suggested by Readability
      *
      * @link https://gist.github.com/965956
      * @link http://www.readability.com/publishers/guidelines#publisher
      */
     public function embed_wrap($cache)
     {
        return '<div class="entry-content-asset">' . $cache . '</div>';
     }
  
     /**
      * Remove unnecessary self-closing tags
      */
     public function remove_self_closing_tags($input)
     {
        return str_replace(' />', '>', $input);
     }
  
     /**
      * Don't return the default description in the RSS feed if it hasn't been changed
      */
     public function remove_default_description($bloginfo)
     {
        $default_tagline = 'Just another WordPress site';
        return ($bloginfo === $default_tagline) ? '' : $bloginfo;
     }
  
     /**
      * Add and remove body_class() classes
      */
     public function body_class($classes)
     {
        // Add post/page slug if not present
        if (is_single() || is_page() && !is_front_page()) {
           if (!in_array(basename(get_permalink()), $classes)) {
              $classes[] = basename(get_permalink());
           }
        }
  
        // Remove unnecessary classes
        $home_id_class = 'page-id-' . get_option('page_on_front');
        $remove_classes = [
           'page-template-default',
           $home_id_class
        ];
        $classes = array_diff($classes, $remove_classes);
  
        return $classes;
     }
  
     /**
      * Clean up output of stylesheet <link> tags
      */
     public function clean_style_tag($input)
     {
        preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
        if (empty($matches[2])) {
           return $input;
        }
        // Only display media if it is meaningful
        $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
        return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
     }
  
     /**
      * Clean up output of <script> tags
      */
     public function clean_script_tag($input)
     { 
        $input = str_replace("type='text/javascript' ", '', $input);
        $input = \preg_replace_callback(
           '/document.write\(\s*\'(.+)\'\s*\)/is',
           function ($m) {
              return str_replace($m[1], addcslashes($m[1], '"'), $m[0]);
           },
           $input
        );
        return str_replace("'", '"', $input);
     }
  
     public function head_cleanup()
     {
        // Originally from https://wpengineer.com/1438/wordpress-header/
        remove_action('wp_head', 'feed_links_extra', 3);
        add_action('wp_head', 'ob_start', 1, 0);
        add_action('wp_head', function () {
           $pattern = '/.*' . preg_quote(esc_url(get_feed_link('comments_' . get_default_feed())), '/') . '.*[\r\n]+/';
           echo preg_replace($pattern, '', ob_get_clean());
        }, 3, 0);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('use_default_gallery_style', '__return_false');
        add_filter('emoji_svg_url', '__return_false');
        add_filter('show_recent_comments_widget_style', '__return_false');
     }
  
     // Move Yoast to bottom
     public function yoasttobottom() {
        return 'low';
     } 

}