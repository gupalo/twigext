<?php

namespace Gupalo\TwigExt;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EncodingExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('base64_encode', 'base64_encode'),
            new TwigFilter('base64_decode', 'base64_decode'),
            new TwigFilter('md5', 'md5'),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('base64_encode', 'base64_encode'),
            new TwigFunction('base64_decode', 'base64_decode'),
            new TwigFunction('md5', 'md5'),
        ];
    }
}
