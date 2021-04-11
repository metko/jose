<?php

namespace Jose;

class View 
{
    public function render() {
        \Timber\Timber::$locations = array(
            ROOT_APP . 'ressources/views'
        );
       $context = \Timber\Timber::context();
        \Timber\Timber::render('templates/homepage.twig', $context);
    }
}