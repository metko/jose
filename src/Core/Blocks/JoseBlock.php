<?php

namespace Jose\Core\Blocks;

use ErrorException;
use Jose\Jose;

class JoseBlock {
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        
        // The name of the block
        if( ! $this->name ) {
            throw new ErrorException('Block must have a name');
        }

        // Hook on acf
        add_action( 'acf/init', [$this, 'register_block'] );
    }

    
    /**
     * register_block
     *
     * @return void
     */
    public function register_block(): void
    {
        // Must have a fields declaration
        if(! method_exists($this, "fields")) {
            throw new ErrorException("Blolck must have a public method fields() wich return an array");
        }

        $this->register_acf_fields();
        $this->register_acf_block();
    }


    public function register_acf_block(): void
    {

        acf_register_block( [
            'name'            => acf_slugify($this->name),
            'title'           => __( $this->name, 'your-text-domain' ),
            'description'     => __( $this->description ?? 'A custom example block.', 'your-text-domain' ),
            'render_callback' => [$this, 'my_acf_block_render_callback'],
            'category'        =>  $this->category ?? "common",
            'icon'            => $this->icon ?? 'admin-comments',
            'keywords'        => $this->keywords ?? ['example'],
        ]);
    }
    
    /**
     * Return global current post
     *
     * @return void
     */
    public function get_post() {
        if ( is_admin() && function_exists( 'acf_maybe_get_POST' ) ) :
            $post_id = intval( acf_maybe_get_POST( 'post_id' ) );
            $post = get_post($post_id);
            if($post){
                return $post; 
            } 
            return null;
        else :
            global $post;
            return $post;
        endif;   
    }
        
    /**
     * register_acf_fields
     *
     * @return void
     */
    public function register_acf_fields(): void
    {
      
        // Use the acf methods to register the fields
        acf_add_local_field_group([
            'key' => 'group-acf-'.acf_slugify($this->name),
            'title' => $this->name,
            'fields' => $this->fields(),
            'location' => [
                [
                    [
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/'.acf_slugify($this->name)
                    ]
                ],
            ],
            'menu_order' => $this->menu_order ?? 0,
            'position' =>  $this->position ?? 'side',
            'style' =>  $this->style ?? 'default',
            'label_placement' =>  $this->label_placement ?? 'top',
            'instruction_placement' =>  $this->instruction_placement ?? 'label',
            'hide_on_screen' =>  $this->hide_on_screen ?? '',
            'active' =>  $this->active ?? true,
            'description' =>  $this->description_acf ?? '',
        ]);
        //dd($this->fields());
    }

    public function my_acf_block_render_callback($block, $content = '', $is_preview = false): void
    {
        $context = [];
        
        // Store block values.
        $context['block'] = $block;
        $context['post'] = $this->get_post();
        
        $context['is_preview'] = $is_preview;
       
        if( isset($this->template) ) {
            $context['template'] = $this->template;
        }else {
            $context['template'] = acf_slugify($this->name);
        }

        // Store field values.
        if(method_exists($this, 'before_render')) {
            $context['fields'] = $this->before_render(get_fields(), $context);
        }else {
            $context['fields'] = get_fields();
        }
      
        Jose::app()->render( 'block/'.$context['template'], $context);
    }

    

}
