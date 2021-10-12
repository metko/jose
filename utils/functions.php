<?php

use Jose\Container;

/*
 * Container instance with key
 */
if (!function_exists('jose')) {

    function jose($container_key = 'app', $params = null, $newInstance = false)
    {
        $container = Container::getInstance();
        return $container->get($container_key, $params, $newInstance);
    }
}

/*
 * Config instance
 */
if (!function_exists('config')) {

    function config($key = null)
    {
        return jose('config');
    }
}

/*
 * Config instance
 */
if (!function_exists('view')) {

    function view($template, $context = [])
    {
        return jose('view')->view($template, $context);
    }
}

