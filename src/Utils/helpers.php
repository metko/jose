<?php

use Jose\Assets;

/**
 * Return url of compiled style or script file
 *
 * @since 1.0.0
 *
 * @param $assets
 *
 * @return String
 */
function asset($asset)
{       
    return Assets::getInstance()->assetPath($asset);
}

/**
 * Return random string
 *
 * @since 1.9.0
 *
 * @param int $length
 * @return String
 */
function random_string($length)
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}


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


function jose()  {
    return \Jose\Jose::app();
}
function context()  {
    return jose()->get_context();
}

