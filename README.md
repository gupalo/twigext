Twig Ext
========

Twig Extensions

Usage
-----

    composer require gupalo/twigext

Symfony
-------

Add to `config/services.yaml`

    Gupalo\TwigExt\:
        resource: '../vendor/gupalo/twigext/src/TwigExt'
        tags: ['twig.extension']

You can customize FormatExtension:

    Gupalo\TwigExt\FormatExtension:
        bind:
            $translations:
                span: div
                format-date-zero: text-warning
            $wrapSpan: false
