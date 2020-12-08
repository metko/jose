<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6fe8ad207371f0f387c9bec77aa672b8
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jose\\Tests\\' => 11,
            'Jose\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jose\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'Jose\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6fe8ad207371f0f387c9bec77aa672b8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6fe8ad207371f0f387c9bec77aa672b8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
