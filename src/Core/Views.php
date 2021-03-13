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
        add_filter( 'timber/twig', [ $this , 'setAddToTwigFunction'] );

    }

    public function setTimberViews() {

        $views = ROOT.Config::getInstance()->get('views_path');
        $blocks = ROOT.Config::getInstance()->get('view_path');


        Timber::$locations = [$views, $blocks];
    }

    public function setAddToTwigFunction(Environment $twig) {
        $twig->addFunction( new Twig_Function( 'img_url', [$this, 'img_url'] ) );
        $twig->addFunction( new Twig_Function( 'dd', [$this, 'debugdie'] ) );
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
    /**
     * @param $key
     * @return string|void
     */
    public function debugdie($var): string
    {
        dd($var);
    }



}