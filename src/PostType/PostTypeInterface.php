<?php
namespace Jose\PostType;

interface PostTypeInterface {

    /*
     * Final method to create the post type
     * @return PostTypeBuilder with the wp_object
     */
    public function create(): PostTypeBuilder;

    /*
    * Pass an array of labels for the post type. A merge will be applied
    * @return PostTypeBuilder with the wp_object
    */
    public function setLabels(Array $labels): PostTypeBuilder;

    /*
    * Pass an array of arguments for the post type. A merge will be applied
    * @return PostTypeBuilder with the wp_object
    */
    public function setArguments(Array $arguments): PostTypeBuilder;

    /*
    * Set the name the base namae of the archive page
    * @return PostTypeBuilder with the wp_object
    */
    public function setTitle(String $name): PostTypeBuilder;

    /*
    * Pass an array of fields with acf builder
    * @return PostTypeBuilder with the wp_object
    */
    public function addFields(\Closure $callback): PostTypeBuilder;

    /*
   * Closure when we call the archive post type
   * @return PostTypeBuilder with the wp_object
   */
    public function onArchive(\Closure $callback): PostTypeBuilder;

    /*
   * Closure when we call the single post type
   * @return PostTypeBuilder with the wp_object
   */
    public function onSingle(\Closure $callback): PostTypeBuilder;

    /*
   * Attach a model with the current post type for timber
   * @return PostTypeBuilder with the wp_object
   */
    public function attachModel(string $model_name): PostTypeBuilder;
}