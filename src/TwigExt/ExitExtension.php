<?php

namespace Gupalo\TwigExt;

use Gupalo\TwigExt\Exceptions\TwigExitException;
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

    public function exit(int $statusCode = 500, mixed $message = ''): void
    {
        throw new TwigExitException($message, $statusCode);
    }
}
