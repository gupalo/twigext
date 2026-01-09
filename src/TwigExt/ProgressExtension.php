<?php

namespace Gupalo\TwigExt;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ProgressExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('progress_class', $this->progressClass(...), ['is_safe' => ['html']]),
            new TwigFilter('progress_percents', $this->progressPercents(...), ['is_safe' => ['html']]),
            new TwigFilter('progress_int', $this->progressInt(...), ['is_safe' => ['html']]),
            new TwigFilter('progress_float', $this->progressFloat(...), ['is_safe' => ['html']]),
        ];
    }

    public function progressClass(float $value, float $maxValue, ?string $color = null): string
    {
        if (abs($maxValue) <= 0.00000001) {
            return '';
        }

        return implode(' ', [
            'pie-progress',
            'pie-progress-share' . max(0, min(100, round($value / $maxValue * 100))),
            ($color !== null) ? 'pie-progress-' . $color : ''
        ]);
    }

    public function progressPercents(?float $value, ?float $maxValue = 1): string
    {
        return $this->progress($value, $maxValue, [$this, 'percents']);
    }

    public function progressInt(?int $value, ?float $maxValue): string
    {
        return $this->progress($value, $maxValue, [$this, 'int']);
    }

    public function progressFloat(?float $value, ?float $maxValue, int $precision = 2): string
    {
        return $this->progress($value, $maxValue, [$this, 'float'], [$precision]);
    }

    /** @param array<mixed>|null $arguments */
    private function progress(int|float|null $value = null, ?float $maxValue = 0, ?callable $funcFormat = null, ?array $arguments = null): string
    {
        if (!$maxValue || !$value || ($value < 0.0001 && $value > -0.0001)) {
            return '';
        }

        $percents = max(0, min(100, $value / $maxValue * 100));

        $result = number_format($percents * 100) . '%';
        if ($funcFormat) {
            $args = [$value];
            if ($arguments) {
                $args = array_merge($args, $arguments);
            }
            $result = call_user_func_array($funcFormat, $args);
        }

        return sprintf('<div class="bar" style="width: %s%%"></div><span class="value">%s</span>', $percents, $result);
    }

    /** @noinspection PhpSameParameterValueInspection */
    private function int(?float $value = null): string
    {
        if ($value === null) {
            return '-';
        }

        return number_format($value);
    }

    /** @noinspection PhpSameParameterValueInspection */
    private function float(?float $value = null, int $decimals = 2): string
    {
        if ($value === null) {
            return '-';
        }

        return number_format($value, $decimals);
    }

    /** @noinspection PhpSameParameterValueInspection */
    private function percents(?float $value = null, int $decimals = 2): string
    {
        if ($value === null) {
            return '-';
        }

        return number_format(100 * $value, $decimals) . '%';
    }
}
