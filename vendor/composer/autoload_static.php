<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1a933c0b1b1f22e1ba86d0e544da6c07
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1a933c0b1b1f22e1ba86d0e544da6c07::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1a933c0b1b1f22e1ba86d0e544da6c07::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1a933c0b1b1f22e1ba86d0e544da6c07::$classMap;

        }, null, ClassLoader::class);
    }
}