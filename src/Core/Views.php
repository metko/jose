<?php

namespace Jose\Core;

use Jose\Assets;
use Jose\Utils\Config;
use Timber\Timber;
use Timber\Twig_Function;
use Twig\Environment;

class Views {

    public function init() {
        $this->setTimberViews();
        $this->setAddToTwigFunction();
    }

    public function setTimberViews() {

        $views = Config::getInstance()->get('views_path');

        //maybe it
        if( !$views ) {
            $views = ROOT."app/views";
        } else {
            $views = ROOT.$views;
        }

        Timber::$locations = $views;
    }

    public function setAddToTwigFunction() {
        add_filter( 'timber/twig', [ $this , 'add_assets_url'] );

    }

    /**
     * My custom Twig functionality.
     *
     * @param Environment $twig
     * @return Environment
     */
    public  function add_assets_url(Environment $twig): Environment
    {
        // Adding a function.
        $twig->addFunction( new Twig_Function( 'img_url', [$this, 'img_url'] ) );
        return $twig;
    }

    /**
     * @param $key
     * @return string|void
     */
    public function img_url($key): string
    {
        return Assets::getInstance()->img_url($key);
    }



}