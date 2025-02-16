<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb5387aa2a7201e8e581a235d50ba8e0f
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'IarElementorWidgets\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'IarElementorWidgets\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb5387aa2a7201e8e581a235d50ba8e0f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb5387aa2a7201e8e581a235d50ba8e0f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb5387aa2a7201e8e581a235d50ba8e0f::$classMap;

        }, null, ClassLoader::class);
    }
}
