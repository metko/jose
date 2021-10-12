<?php

namespace Jose\PostType;

use Jose\Exception\ClassDoesntExistException;

class PostTypeBuilder implements PostTypeInterface
{

    use DefaultPostTypeOption;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $plural_name;

    /**
     * @var array
     */
    public $labels;

    /**
     * @var array
     */
    public $arguments;

    /*
     * \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public $fields;

    /**
     * @object wp_post_type
     */
    public $wp_post_type;

    /**
     * On archive post type
     * @closure
     */
    public $on_archive;

    /**
     * On single post type
     * @closure
     */
    public $on_single;

    /**
     * The object model class for timber
     * @object
     */
    public $model;

    /**
     * The archive page name
     * @object
     */
    public $title;

    /**
     * PostTypeBuilder constructor.
     * @param $id
     * @param $name
     * @param $plural_name
     */
    public function __construct(string $id, string $name, string $plural_name) {
        $this->id = $id;
        $this->name = $name;
        $this->plural_name = $plural_name;

        // Generate default labels
        $this->generateLabels();

        // Generate default arguments
        $this->generateArguments();
    }

    /**
     *
     * Create the post type
     *
     * @return mixed
     */
    public function create(): PostTypeBuilder
    {
        $this->arguments['labels'] = $this->labels;
        if (!$this->title) {
            $this->title = $this->labels['all_items'];
        }
        $this->wp_post_type = register_post_type(
            $this->id, // Post type name. Max of 20 characters. Uppercase and spaces not allowed.
            $this->arguments      // Arguments for post type.
        );
        jose('post_type')->all[$this->id] = $this;
        return jose('post_type')->get($this->id);
    }

    /**
     * @param array $labels
     * @return PostTypeBuilder
     */
    public function setLabels(array $labels): PostTypeBuilder
    {
        $this->labels =  array_merge($this->labels, $labels);
        return $this;
    }

    /**
     * @param array $arguments
     * @return PostTypeBuilder
     */
    public function setArguments(array $arguments): PostTypeBuilder
    {
        $this->arguments =  array_merge($this->arguments, $arguments);
        return $this;
    }

    /**
     * @param array $arguments
     * @return PostTypeBuilder
    */
    public function setTitle(string $name): PostTypeBuilder
    {
        $this->title = $name;
        return $this;
    }

    /**
     *
     * Create custom acf fields for the current post type
     *
     * @param $callback
     * @return mixed
     */
    public function addFields(\Closure $callback): PostTypeBuilder
    {

        $builder = jose('acf_builder', [$this->id . '_acf_data'], true);

        // Callback user
        $callback->call($this, $builder);

        // Set the location for the current post type
        $builder->setLocation('post_type', '==', $this->id);

        add_action('acf/init', function() use ($builder) {
            acf_add_local_field_group($builder->build());
        });

        // add the acf fields conf to the object
        $this->fields = $builder;

        return $this;
    }


    /**
     * On archive query of the post type
     * @param $callback
     * @return $this
     */
    public function onArchive(\Closure $callback): PostTypeBuilder
    {

        add_filter( 'pre_get_posts', function ($query) use($callback) {
            if(!is_admin() && is_archive() && $query->query['post_type'] === $this->id) {
                return $callback->call($this, $query);
            }
        } );
        $this->on_archive = $callback;
        return $this;
    }

    /**
     * On single query of the post type
     * @param $callback
     * @return $this
     */
    public function onSingle(\Closure $callback): PostTypeBuilder
    {
        add_filter( 'pre_get_posts', function ($query) use($callback) {
            if(!is_admin() && is_single() && $query->query['post_type'] === $this->id) {
                return $callback->call($this, $query);
            }
        } );
        $this->on_single = $callback;
        return $this;
    }


    /**
     * Attach a model for timber post class
     * @param $model_name
     * @return PostTypeBuilder
     * @throws ClassDoesntExistException
     */
    public function attachModel(string $model_name): PostTypeBuilder
    {
        if(class_exists($model_name)) {
            jose('post_type_class')->add($this->id, $model_name);
            $this->model = new $model_name();
        }else {
            throw new ClassDoesntExistException('Class model ' . $model_name . ' doesnt exist');
        }
        return $this;
    }

}