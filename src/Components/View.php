<?php

namespace Jose\Components;
use Jenssegers\Blade\Blade;

class View 
{
    protected $blade = null;

    public function __construct () {
        $this->blade = new Blade(ROOT_APP. 'App/Views/', ROOT_APP . '/storage/cache/');
        $this->setDirectives();
        $this->setUserDirectives();
    }

    public function view($view) {
        echo $this->blade->render($view, array_merge(jose('context')->context, ['context' => jose('context')->context]));
    }

    public function setDirectives() {
        $this->blade->directive('wp_head', function () {
            ob_start();
            wp_head();
            return ob_get_contents();
        });
        $this->blade->directive('wp_footer', function () {
            ob_start();
            wp_footer();
            return ob_get_contents();
        });
    }

    public function setUserDirectives() {
        if (class_exists('\App\Providers\View')) {
            (new \App\Providers\View())->register($this->blade);
        }
    }
}