<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit68c139ea9f79e5bb3956c213fb52c6aa
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Girgit\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Girgit\\' => 
        array (
            0 => __DIR__ . '/../..' . '/components/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit68c139ea9f79e5bb3956c213fb52c6aa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit68c139ea9f79e5bb3956c213fb52c6aa::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
