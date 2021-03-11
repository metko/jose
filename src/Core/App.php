<?php

namespace Jose\Core;

use Jose\Assets;
use Jose\Core\Theme\RegisterMenu;
use Jose\Core\Theme\Theme;
use Jose\Utils\Config;
use Timber\Timber;

class App  {

    // Represent the Context oject instance
    public $context = null;
    private static $instance = null;


    /**
     * @return App|null
     */
    public static function getInstance(): ?App
    {
        if( ! self::$instance) {
            self::$instance = new App();
        }
        return self::$instance;
    }

    /**
     * @return $this
     */
    public function app(): App
    {
        $this->context = Context::getInstance()->setContext();
        return $this;
    }

    /**
     * @param null $key
     * @return array|string
     */
    public function context($key = null)
    {
        return Context::getInstance()->get($key);
    }

    /**
     * @param null $key
     * @return array|string|null
     */
    public function config($key = null)
    {
        return Config::getInstance()->get($key);
    }


    /**
     * Pass varibale into the context
     *
     * @param  mixed $param1
     * @param  mixed $param2
     * @return self
     */
    public function pass($param1 = null, $param2 = null): App
    {
        $this->context->pass($param1, $param2);
        return $this;
    }


    /**
     * Use Timber render function to output twig file, with context and cache
     *
     * @param mixed $template
     * @param array $context
     * @return void
     */
    public function render(String $template = null, $context = [])
    {
        $context = array_merge( $this->beforeRender(), $context);

        // if i'ts a page
        // dd(is_page());

        // if i's a single
//        dd(is_single());

       //  dd(is_singular());

        //dd(get_page_template_slug());
        if(! $template) {
            $template = $this->getTemplate($context);
        }



        $arrayTemplate = array_map([$this, 'add_extension'], is_array($template) ? $template: [$template]);
        return Timber::render($arrayTemplate, $context);
    }

    /**
     * @param $string
     * @return string
     */
    private function add_extension($string): string
    {
        return $string . '.twig';
    }

    private function getTemplate($context) {

        // If the post have a page template set in admin, use it
        if(get_page_template_slug()) {
            // we juste remove the extension since timber need to call a twig
            return str_replace( '.php', '', get_page_template_slug());
        }

        if(is_page() && is_singular()) {
            return [
                'page-'.$context['post']->slug,
                'page-'.$context['post']->ID,
                'page'];
        }

        // if it's archive post type
        if(is_archive() || is_post_type_archive() ) {
            return [
                'archive-'.get_post_type(),
                'archive'
            ];
        }

        return 'something...';

    }


    /**
     * Auto inject post model to context
     * Depending of the current query
     *
     * @return void
     */
    private function beforeRender()
    {
        RegisterMenu::getMenus();
        if(is_page_template() && count( $this->context->get('posts'))) {

            $page = $this->context->get('posts')[0];
            $this->context->pass('post', $page);
            $this->context->pass('wp_title', $page->post_title);
        }
        if(is_singular() && count(  $this->context->get('posts'))) {
            // TODO
            $singular_post =  $this->context->get('posts')[0];
            $this->context->pass('post', $singular_post);
            $this->context->pass('wp_title', $singular_post->post_title);
        }

        if(is_404()) {
        }
        return $this->context->get();
    }



    /**
     * @param $name
     * @return BuildACFFields
     * Build ACF fields
     */
    public function addFields($name): BuildACFFields
    {
        return new BuildACFFields($name);
    }

    /**
     * @param $name
     * @return BuildACFFields
     * Build ACF blocks
     */
    public function addBlock($name )
    {
        return new BuildACFFields($name, true);
    }

}