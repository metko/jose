<?php

function getManifest($key) {
    $manifest_string = file_get_contents(get_template_directory() . '/assets/manifest.json');
    $manifest_array  = json_decode($manifest_string, true);

    if(array_key_exists($key, $manifest_array)) {
        return $manifest_array[$key];
    }   
    
    if(WP_ENV == "development") {
        // throw new ErrorException('Array key ' . $key .' doesnt exist in manifest.json');
    }

    return false;
}

/**
 * Return url of compiled style or script file
 *
 * @since 1.0.0
 *
 * @param $key
 *
 * @return string
 */
function assets($key)
{       

    if(getManifest($key)) {
        return get_stylesheet_directory_uri() . '/assets' . getManifest($key);
    }
    return null;
}


/**
 * Return random string
 *
 * @since 1.9.0
 *
 * @param int $length
 * @return void
 */
function random_string($length)
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}
