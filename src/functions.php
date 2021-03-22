<?php

use Jose\Container;

/*
 * Container instance with key
 */
if (!function_exists('jose')) {

    function jose($container_key, $params = null, $newInstance = false)
    {
        $container = Container::getInstance();

        return $container->get($container_key, $params, $newInstance);
    }
}

/*
 * Config instance
 */
if (!function_exists('j_config')) {

    function j_config()
    {
        return jose('config');
    }
}
