<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5860ef6ab4c29e65024360c914d3e262
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Web\\' => 4,
        ),
        'T' => 
        array (
            'TemplateFunctions\\' => 18,
        ),
        'C' => 
        array (
            'Clsk\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Web\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Web',
        ),
        'TemplateFunctions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Web/Programs/TemplateFunctions',
        ),
        'Clsk\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Core',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5860ef6ab4c29e65024360c914d3e262::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5860ef6ab4c29e65024360c914d3e262::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5860ef6ab4c29e65024360c914d3e262::$classMap;

        }, null, ClassLoader::class);
    }
}
