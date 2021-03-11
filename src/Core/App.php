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
     * @return array
     */
    public function context(): array
    {
        return Context::getInstance()->get();
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
    public function render(String $template, $context = [])
    {
        $context = array_merge( $this->beforeRender(), $context);
        return Timber::render($template.'.twig', $context);
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
        if(is_page_template() && count( $this->context->get()['posts'])) {

            $page = $this->context->get()['posts'][0];
            $this->context->pass('post', $page);
            $this->context->pass('wp_title', $page->post_title);
        }
        if(is_singular() && count(  $this->context->get()['posts'])) {
            // TODO
            $singular_post =  $this->context->get()['posts'][0];
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