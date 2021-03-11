<?php

use Jose\Assets;
use Jose\Core\App;
use Jose\Jose;


if( ! function_exists('pathToNamespace')) {
    /**
     * Convert a path into a namespace
     *
     * @param  string $path
     * @return string
     */
    function pathToNamespace(string $path) :string
    {
        $namespaceArray = explode('/', $path);
        $namespacePath = "\\";
        foreach($namespaceArray as $key) {
            $namespacePath .= ucfirst($key);
            $namespacePath .= "\\";
        }

        return $namespacePath;
    }
}



if( ! function_exists('jose')) {

    /**
     * @return App|null
     * @throws ErrorException
     */
    function jose(): ?App
    {
        return Jose::app();
    }
}

if( ! function_exists('j_context')) {
    /**
     * @param null $key
     * @return array|string|null
     * @throws ErrorException
     */
    function j_context($key = null)
    {
        return jose()->context($key);
    }
}


if( ! function_exists('j_config')) {
    /**
     * @param null $key
     * @return array|string|null
     * @throws ErrorException
     */
    function j_config($key = null)
    {
        return jose()->config($key);
    }
}

if( ! function_exists('j_style')) {
    /**
     * @param null $key
     * @return array|string|null
     */
    function j_style($key = null)
    {
        return Assets::getInstance()->css($key);
    }
}

if( ! function_exists('j_script')) {
    /**
     * @param null $key
     * @return array|string|null
     * @throws ErrorException
     */
    function j_script($key = null)
    {
        return Assets::getInstance()->js($key);
    }
}

if( ! function_exists('accessibleArray')) {

    /**
     * @param $value
     * @return bool
     */
    function accessibleArray($value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}

if( ! function_exists('existKeyInArray')) {

    /**
     * @param $array
     * @param $key
     * @return bool
     */
    function existKeyInArray($array, $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }
}




