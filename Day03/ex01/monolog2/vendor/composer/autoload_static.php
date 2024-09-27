<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit45b3b6bcab8e89b5b836b89d182bbbfe
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit45b3b6bcab8e89b5b836b89d182bbbfe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit45b3b6bcab8e89b5b836b89d182bbbfe::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit45b3b6bcab8e89b5b836b89d182bbbfe::$classMap;

        }, null, ClassLoader::class);
    }
}
