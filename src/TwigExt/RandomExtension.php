<?php

namespace Gupalo\TwigExt;

use Gupalo\ArrayUtils\ArrayRandom;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RandomExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('random_day', [$this, 'randomDay'], ['is_safe' => ['html']]),
            new TwigFunction('random_hour', [$this, 'randomHour'], ['is_safe' => ['html']]),
            new TwigFunction('random_item', [$this, 'randomItem'], ['is_safe' => ['html']]),
            new TwigFunction('random_items', [$this, 'randomItems'], ['is_safe' => ['html']]),
        ];
    }

    public function randomDay(int $max = 100, string $salt = '_random_salt_day'): int
    {
        $hash = abs(crc32(md5(date('Ymd') . $salt)));

        return ($hash % $max) + 1;
    }

    public function randomHour(int $max = 100, string $salt = '_random_salt_hour'): int
    {
        $hash = abs(crc32(md5(date('YmdH') . $salt)));

        return ($hash % $max) + 1;
    }

    public function randomItem(iterable $items): mixed
    {
        return ArrayRandom::pick($items);
    }

    public function randomItems(iterable $items, int $count = 1, bool $preserveKeys = false, mixed $default = []): mixed
    {
        return ArrayRandom::pickMultiple(
            items: $items,
            count: $count,
            preserveKeys: $preserveKeys,
            default: $default,
        );
    }
}
