<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf704588f8935be822901417667a93ab7
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf704588f8935be822901417667a93ab7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf704588f8935be822901417667a93ab7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf704588f8935be822901417667a93ab7::$classMap;

        }, null, ClassLoader::class);
    }
}
