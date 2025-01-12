<?php

namespace Gupalo\TwigExt;

use Gupalo\Json\Json;
use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JsonExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('json_decode', [$this, 'jsonDecode'], ['is_safe' => ['html']]),
        ];
    }

    public function jsonDecode(?string $s = null): array
    {
        try {
            $result = Json::toArray($s);
        } catch (Throwable) {
            $result = [];
        }

        return $result;
    }
}
