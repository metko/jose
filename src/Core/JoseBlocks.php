<?php

namespace Jose\Core;

use DirectoryIterator;
use ErrorException;
use Jose\Core\PostClassMap;
use Jose\Jose;
use Jose\Utils\Config;
use Jose\Utils\Finder;
use Timber\Timber;

class JoseBlocks {


    /**
     * @var string
     */
    private $blocks_path;

    public function __construct() {
        if(! $this->blocks_path = Config::getInstance()->get("block_path") ) {
            $this->blocks_path = 'jose/blocks';
        }
    }

    public function init() {
        if( ! $this->blocks_path) return;

        add_filter( 'timber/acf-gutenberg-blocks-templates', function () {
            return [ ROOT.$this->blocks_path]; // default: ['views/blocks']
        } );

        if ( function_exists( 'add_action' ) && function_exists( 'acf_register_block' ) ) {
            add_action( 'acf/init', [$this, 'timber_block_init'], 10, 0 );
        }


    }


    /**
     * Create blocks based on templates found in Timber's "views/blocks" directory
     */
    public function timber_block_init() {

        // Get an array of directories containing blocks.
        $directories = timber_block_directory_getter();
        // Check whether ACF exists before continuing.
        foreach ( $directories as $dir ) {

            // Sanity check whether the directory we're iterating over exists first.
            if ( ! Finder::getInstance()->file_exists($dir) ) {
                return;
            }

            // Iterate over the directories provided and look for templates.
            $template_directory = new DirectoryIterator( $dir  );
            foreach ( $template_directory as $template ) {

                if ( $template->isDot() || $template->isDir() ) {
                    continue;
                }

                $file_parts = pathinfo( $template->getFilename() );
                if ( 'twig' !== $file_parts['extension'] ) {
                    continue;
                }

                // Strip the file extension to get the slug.
                $slug = $file_parts['filename'];

                // Get header info from the found template file(s).
                $file_path    =  $dir . "/${slug}.twig";
                $file_headers = get_file_data(
                    $file_path,
                    array(
                        'title'                      => 'Title',
                        'description'                => 'Description',
                        'category'                   => 'Category',
                        'icon'                       => 'Icon',
                        'keywords'                   => 'Keywords',
                        'mode'                       => 'Mode',
                        'align'                      => 'Align',
                        'post_types'                 => 'PostTypes',
                        'supports_align'             => 'SupportsAlign',
                        'supports_mode'              => 'SupportsMode',
                        'supports_multiple'          => 'SupportsMultiple',
                        'supports_anchor'            => 'SupportsAnchor',
                        'enqueue_style'              => 'EnqueueStyle',
                        'enqueue_script'             => 'EnqueueScript',
                        'enqueue_assets'             => 'EnqueueAssets',
                        'supports_custom_class_name' => 'SupportsCustomClassName',
                        'supports_reusable'          => 'SupportsReusable',
                        'example'                    => 'Example',
                        'supports_jsx'               => 'SupportsJSX',
                        'parent'                     => 'Parent',
                        'default_data'               => 'DefaultData',
                    )
                );

                if ( empty( $file_headers['title'] ) || empty( $file_headers['category'] ) ) {
                    continue;
                }

                // Keywords exploding with quotes.
                $keywords = str_getcsv( $file_headers['keywords'], ' ', '"' );

                // Set up block data for registration.
                $data = array(
                    'name'                       => $slug,
                    'title'                      => $file_headers['title'],
                    'description'                => $file_headers['description'],
                    'category'                   => $file_headers['category'],
                    'icon'                       => $file_headers['icon'],
                    'keywords'                   => $keywords,
                    'mode'                       => $file_headers['mode'],
                    'align'                      => $file_headers['align'],
                    'render_callback'            => 'timber_blocks_callback',
                    'enqueue_assets'             => $file_headers['enqueue_assets'],
                    'supports_custom_class_name' => 'SupportsCustomClassName',
                    'supports_reusable'          => 'SupportsReusable',
                    'default_data'               => $file_headers['default_data'],
                );

                // Removes empty defaults.
                $data = array_filter( $data );

                // If the PostTypes header is set in the template, restrict this block
                // to those types.
                if ( ! empty( $file_headers['post_types'] ) ) {
                    $data['post_types'] = explode( ' ', $file_headers['post_types'] );
                }
                // If the SupportsAlign header is set in the template, restrict this block
                // to those aligns.
                if ( ! empty( $file_headers['supports_align'] ) ) {
                    $data['supports']['align'] = in_array( $file_headers['supports_align'], array( 'true', 'false' ), true ) ?
                        filter_var( $file_headers['supports_align'], FILTER_VALIDATE_BOOLEAN ) :
                        explode( ' ', $file_headers['supports_align'] );
                }
                // If the SupportsMode header is set in the template, restrict this block
                // mode feature.
                if ( ! empty( $file_headers['supports_mode'] ) ) {
                    $data['supports']['mode'] = 'true' === $file_headers['supports_mode'] ? true : false;
                }
                // If the SupportsMultiple header is set in the template, restrict this block
                // multiple feature.
                if ( ! empty( $file_headers['supports_multiple'] ) ) {
                    $data['supports']['multiple'] = 'true' === $file_headers['supports_multiple'] ? true : false;
                }
                // If the SupportsAnchor header is set in the template, restrict this block
                // anchor feature.
                if ( ! empty( $file_headers['supports_anchor'] ) ) {
                    $data['supports']['anchor'] = 'true' === $file_headers['supports_anchor'] ? true : false;
                }

                // If the SupportsCustomClassName is set to false hides the possibilty to
                // add custom class name.
                if ( ! empty( $file_headers['supports_custom_class_name'] ) ) {
                    $data['supports']['customClassName'] = 'true' === $file_headers['supports_custom_class_name'] ? true : false;
                }

                // If the SupportsReusable is set in the templates it adds a posibility to
                // make this block reusable.
                if ( ! empty( $file_headers['supports_reusable'] ) ) {
                    $data['supports']['reusable'] = 'true' === $file_headers['supports_reusable'] ? true : false;
                }

                // Gives a possibility to enqueue style. If not an absoulte URL than adds
                // theme directory.
                if ( ! empty( $file_headers['enqueue_style'] ) ) {
                    if ( ! filter_var( $file_headers['enqueue_style'], FILTER_VALIDATE_URL ) ) {
                        $data['enqueue_style'] = get_template_directory_uri() . '/' . $file_headers['enqueue_style'];
                    } else {
                        $data['enqueue_style'] = $file_headers['enqueue_style'];
                    }
                }

                // Gives a possibility to enqueue script. If not an absoulte URL than adds
                // theme directory.
                if ( ! empty( $file_headers['enqueue_script'] ) ) {
                    if ( ! filter_var( $file_headers['enqueue_script'], FILTER_VALIDATE_URL ) ) {
                        $data['enqueue_script'] = get_template_directory_uri() . '/' . $file_headers['enqueue_script'];
                    } else {
                        $data['enqueue_script'] = $file_headers['enqueue_script'];
                    }
                }
                // Support for experimantal JSX.
                if ( ! empty( $file_headers['supports_jsx'] ) ) {
                    // Leaving the experimaental part for 2 versions.
                    $data['supports']['__experimental_jsx'] = 'true' === $file_headers['supports_jsx'] ? true : false;
                    $data['supports']['jsx']                = 'true' === $file_headers['supports_jsx'] ? true : false;
                }

                // Support for "example".
                if ( ! empty( $file_headers['example'] ) ) {
                    $json                       = json_decode( $file_headers['example'], true );
                    $example_data               = ( null !== $json ) ? $json : array();
                    $example_data['is_example'] = true;
                    $data['example']            = array(
                        'attributes' => array(
                            'mode' => 'preview',
                            'data' => $example_data,
                        ),
                    );
                }

                // Support for "parent".
                if ( ! empty( $file_headers['parent'] ) ) {
                    $data['parent'] = str_getcsv( $file_headers['parent'], ' ', '"' );
                }

                // Merges the default options.
                $data = timber_block_default_data( $data );

                // Register the block with ACF.
                acf_register_block_type( $data );
            }
        }
    }



}
