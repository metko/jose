<?php

namespace Jose\Components;

class Templater {


	/**
	 * The array of templates that this plugin tracks.
	 */
	public $templates;

    public function getInstance()
    {
        if(!$this->instance) {
            $this->instance = new Templater();
        }
        return $this->instance;
    }
    
	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	public function __construct() {
        add_action( 'after_setup_theme', [$this, 'init']);

		$this->templates = \Jose\Router::routes('template');
        // Add a filter to the wp 4.7 version attributes metabox
        add_filter('theme_page_templates', [$this, 'add_new_template']);

		// Add a filter to the save post to inject out template into the page cache
		add_filter('wp_insert_post_data', [$this, 'register_project_templates']);	
	} 

    public function init () {

    }

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, array_flip($this->templates) );
		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list. 
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		} 

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	} 

} 
