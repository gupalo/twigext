<?php

namespace Gupalo\TwigExt;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('truncate', [$this, 'truncate'], ['is_safe' => ['html']]),
            new TwigFilter('underscore', [$this, 'underscore'], ['is_safe' => ['html']]),
            new TwigFilter('mask_password', [$this, 'maskPassword'], ['is_safe' => ['html']]),
            new TwigFilter('safe_title', [$this, 'safeTitle'], ['is_safe' => ['html']]),
            new TwigFilter('ucfirst', [$this, 'ucfirst'], ['is_safe' => ['html']]),
        ];
    }

    public function truncate(string $s = null, int $length = 100): string
    {
        if (mb_strlen($s) < $length) {
            return $s ?? '';
        }

        return mb_substr($s, 0, $length) . '&hellip;';
    }

    public function underscore(string $s = null, $character = '_'): string
    {
        $result = '';

        $len = mb_strlen($s);
        for ($i = 0; $i < $len; $i++) {
            $c = mb_substr($s, $i, 1);
            if ($i === 0) {
                $c = mb_strtolower($c);
            }
            $result .= (!preg_match('#^[A-Z]$#', $c)) ? $c : $character . mb_strtolower($c);
        }

        return $result;
    }

    public function maskPassword(string $s = null): string
    {
        return str_pad(mb_substr($s, 0, 3), 8, '*');
    }

    public function safeTitle(string $s = null): string
    {
        return str_replace('"', '\'', $s);
    }

    public function ucfirst(string $s = null): string
    {
        return ucfirst($s);
    }
}
