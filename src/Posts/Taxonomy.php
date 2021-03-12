<?php



namespace Jose\Posts;
use ErrorException;
use Jose\Core\Exceptions\MissingPostTypeInTaxonomiesException;
use Timber\Post;

class Taxonomy {

    // public static $_pt_name = null;
    public $name = null;
    public $public_name = null;
    public $plural_name = null;
    public $labels = null;
    public $arguments = null;
    public $hierarchical = null;
    public $post_types = null;

    /**
     * Register post type methods
     *
     * @throws MissingPostTypeInTaxonomiesException
     */
    public  function create ()
    {
        $this->checkNames();

        if( ! $this->labels){
            $this->setLabels([]);
        }

        if( ! $this->arguments){
            $this->setArguments([]);
        }
        if( $this->hierarchical === null){
            $this->isHierarchical();
        }

        $this->arguments['labels'] = $this->labels;
        
        if( ! $this->post_types) {
            throw new MissingPostTypeInTaxonomiesException($this->name, get_called_class());
        }

        return register_taxonomy($this->name, $this->post_types ,$this->arguments);

    }

    public function checkNames() {
        if( ! $this->public_name){
            $this->setPublicName(ucFirst($this->name));
        }

        if( ! $this->plural_name){
            $this->setPluralName($this->public_name . 's');
        }
    }

    /**
     * get_post_type_public_name
     *
     * @param $name
     * @param array $postType
     * @return Taxonomy
     */
    public function setName($name, Array $postType): Taxonomy
    {
        $this->name = strtolower($name);
        $this->post_types = $postType;
        return $this;
    }

    /**
     * get_post_type_public_name
     *
     * @param $name
     * @return Taxonomy
     */
    public function setPublicName($name): Taxonomy
    {
        $this->public_name = $name;
        return $this;
    }

    /**
     * get_post_type_public_name
     *
     * @param $name
     * @return Taxonomy
     */
    public function setPluralName($name): Taxonomy
    {
        $this->plural_name = $name;
        return $this;
    }

    /**
     * get_post_type_public_name
     *
     * @param bool $bool
     * @return Taxonomy
     */
    public function isHierarchical($bool = true): Taxonomy
    {
        $this->hierarchical = $bool;
        return $this;
    }


    /**
     * Generate the post type arguments if needed
     *
     * @param array $arguments
     * @return Taxonomy
     */
    public function setArguments($arguments = []): Taxonomy
    {

        $this->checkNames();
        $public_name = $this->public_name;
        $plural_name = $this->plural_name;

        $auto_arguments = array(
            'hierarchical'      =>  $this->hierarchical ?? true,
            'show_ui'           =>  true,
            'show_admin_column' =>  true,
            'show_in_rest'      =>  true,
            'query_var'         =>  true,
            'rewrite'           => array( 'slug' => strtolower($plural_name) ),
        );
        $this->arguments = array_merge($auto_arguments, $arguments);
        
        return $this;

    }


    /**
     * Generate the post type label if needed
     *
     * @param array $labels
     * @return Taxonomy
     */
    public function setLabels($labels = []): Taxonomy
    {
        $this->checkNames();

        // Check if we have a public singular name 
        $public_name = $this->public_name;
        $plural_name = $this->plural_name;
  
        $auto_labels =  [
            'name'                       => _x( ucFirst($public_name), 'Taxonomy general name',  "jose" ),
            'singular_name'              => _x( ucFirst($public_name), 'taxonomy singular name',  "jose" ),
            'search_items'               => __( 'Search '.$plural_name,  "jose" ),
            'popular_items'              => __( 'Popular '.$plural_name,  "jose" ),
            'all_items'                  => __( 'All '.$plural_name,  "jose" ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit '.$public_name,  "jose" ),
            'update_item'                => __( 'Update '.$public_name,  "jose" ),
            'add_new_item'               => __( 'Add new '.$public_name,  "jose" ),
            'new_item_name'              => __( 'New '.$public_name.' name',  "jose" ),
            'separate_items_with_commas' => __( 'Separate '.$plural_name.' with commas',  "jose" ),
            'add_or_remove_items'        => __( 'Add or remove '.$plural_name,  "jose" ),
            'choose_from_most_used'      => __( 'Choose from the most used '.$plural_name,  "jose" ),
            'not_found'                  => __( 'No '.$plural_name.' found.',  "jose" ),
            'menu_name'                  => __( ucFirst($plural_name),  "jose" ),
        ];

        $this->labels = array_merge($auto_labels, $labels);
        return $this;
    }

}
