<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInited81ff801da202ed89a9dfd6d7862708
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInited81ff801da202ed89a9dfd6d7862708::$classMap;

        }, null, ClassLoader::class);
    }
}
