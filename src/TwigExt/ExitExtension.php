<?php

namespace Gupalo\TwigExt;

use RuntimeException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ExitExtension extends AbstractExtension
{
    public function __construct(
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('exit', [$this, 'exit'], ['is_safe' => ['html']]),
        ];
    }

    public function exit(): void
    {
        throw new RuntimeException('');
    }
}
