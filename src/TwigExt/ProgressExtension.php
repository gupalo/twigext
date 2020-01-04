<?php

namespace Gupalo\TwigExt;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ProgressExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('progress_class', [$this, 'progressClass'], ['is_safe' => ['html']]),
            new TwigFilter('progress_percents', [$this, 'progressPercents'], ['is_safe' => ['html']]),
            new TwigFilter('progress_int', [$this, 'progressInt'], ['is_safe' => ['html']]),
            new TwigFilter('progress_float', [$this, 'progressFloat'], ['is_safe' => ['html']]),
        ];
    }

    public function progressClass(float $value, float $maxValue, $color = null)
    {
        if ($maxValue === 0) {
            return '';
        }

        return implode(' ', [
            'pie-progress',
            'pie-progress-share' . max(0, min(100, round($value / $maxValue * 100))),
            ($color !== null) ? 'pie-progress-' . $color : ''
        ]);
    }

    public function progressPercents(?float $value, $maxValue = 1)
    {
        return $this->progress($value, $maxValue, [$this, 'percents']);
    }

    public function progressInt(?int $value, $maxValue)
    {
        return $this->progress($value, $maxValue, [$this, 'int']);
    }

    public function progressFloat(?float $value, $maxValue, $precision = 2)
    {
        return $this->progress($value, $maxValue, [$this, 'float'], [$precision]);
    }

    /** @noinspection PhpOptionalBeforeRequiredParametersInspection */
    private function progress($value = null, ?float $maxValue, callable $funcFormat, $arguments = null)
    {
        if (!$maxValue || !$value || ($value < 0.0001 && $value > -0.0001)) {
            return '';
        }

        $percents = max(0, min(100, $value / $maxValue * 100));

        $args = [$value];
        if ($arguments) {
            $args = array_merge($args, $arguments);
        }
        $result = call_user_func_array($funcFormat, $args);

        return sprintf('<div class="bar" style="width: %s%%"></div><span class="value">%s</span>', $percents, $result);
    }

    private function int(float $value = null)
    {
        if ($value === null) {
            return '-';
        }

        return number_format($value);
    }

    private function float(float $value = null, $precision = 2)
    {
        if ($value === null) {
            return '-';
        }

        return number_format($value, $precision);
    }

    private function percents(float $value = null, $precision = 2): string
    {
        if ($value === null) {
            return '-';
        }

        return number_format(100 * $value, $precision) . '%';
    }
}
