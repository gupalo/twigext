<?php /** @noinspection PhpMethodMayBeStaticInspection */

namespace Gupalo\TwigExt;

use DateTimeInterface;
use Gupalo\DateUtils\DateUtils;
use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('int', [$this, 'int'], ['is_safe' => ['html']]),
            new TwigFilter('float', [$this, 'float'], ['is_safe' => ['html']]),
            new TwigFilter('money', [$this, 'float'], ['is_safe' => ['html']]),
            new TwigFilter('smart_float', [$this, 'smartFloat'], ['is_safe' => ['html']]),
            new TwigFilter('percents', [$this, 'percents'], ['is_safe' => ['html']]),
            new TwigFilter('date_full', [$this, 'dateFull'], ['is_safe' => ['html']]),
            new TwigFilter('date_short', [$this, 'dateShort'], ['is_safe' => ['html']]),
            new TwigFilter('date_noyear', [$this, 'dateNoYear'], ['is_safe' => ['html']]),
        ];
    }

    public function int($value = null)
    {
        if ($value === null) {
            return '<span class="text-muted">-</span>';
        }
        if (is_array($value)) {
            return 'ERR';
        }

        if ($value < 0.0001) {
            return '<span class="text-muted">0</span>';
        }

        return number_format($value);
    }

    public function float($value = null, int $precision = 2, int $muteDot = 0)
    {
        if ($value === null) {
            return '<span class="text-muted">-</span>';
        }
        if (is_array($value)) {
            return 'ERR';
        }

        if ($value < 0.0001 && $value > -0.0001) {
            return '<span class="text-muted">0</span>';
        }

        $result = number_format($value, $precision);
        if ($muteDot > 0) {
            $result = preg_replace('#\.\d+$#', '<span class="text-muted">$0</span>', $result);
        } elseif ($muteDot === 0) {
            $result = preg_replace('#\.0+$#', '<span class="text-muted">$0</span>', $result);
        }

        return $result;
    }

    public function money($value = null, int $precision = 2)
    {
        if ($value === null) {
            return '<span class="text-muted">-</span>';
        }
        if (is_array($value)) {
            return 'ERR';
        }

        if ($value < 0.0001 && $value > -0.0001) {
            return '<span class="text-muted">0</span>';
        }

        $result = number_format($value, $precision);
        $result = preg_replace('#\.\d+$#', '<span class="text-muted">$0</span>', $result);

        return $result;
    }

    public function smartFloat($value = null, int $precision = 2, int $muteDot = 0)
    {
        if ($value === null) {
            return '<span class="text-muted">-</span>';
        }
        if (is_array($value)) {
            return 'ERR';
        }

        if ($value < 0.0001) {
            return '<span class="text-muted">0</span>';
        }

        $result = number_format($value, $precision);
        $result = preg_replace('#^([^0]\d*)\.\d+$#', '$1', $result);

        if ($muteDot > 0) {
            $result = preg_replace('#\.\d+$#', '<span class="text-muted">$0</span>', $result);
        } elseif ($muteDot === 0) {
            $result = preg_replace('#\.0+$#', '<span class="text-muted">$0</span>', $result);
        }

        return $result;
    }

    public function percents($value = null, $precision = 2): string
    {
        if ($value === null) {
            return '<span class="text-muted">-</span>';
        }
        if (is_array($value)) {
            return 'ERR';
        }

        $class = null;
        if (abs($value) < 0.0001) {
            $class = 'text-muted';
        } elseif ($value < 0) {
            $class = 'text-danger';
        }

        $template = $class ? '<span class="' . $class . '">%s%%</span>' : '%s%%';

        return sprintf($template, number_format(100 * $value, $precision));
    }

    public function dateFull($value, $default = '-')
    {
        if (!$value || $value <= DateUtils::create('1970-01-01')) {
            return $default;
        }

        return $this->dateFormat($value, 'Y-m-d H:i:s');
    }

    public function dateShort($value, $default = '-')
    {
        if (!$value) {
            return $default;
        }

        return $this->dateFormat($value, 'Y-m-d');
    }

    private function dateFormat($value, string $format = 'Y-m-d H:i:s')
    {
        if ($value === null) {
            $result = '<span class="text-muted">-</span>';
        } elseif ($value instanceof DateTimeInterface) {
            $result = $value->format($format);
        } elseif (is_string($value)) {
            try {
                $result = DateUtils::create($value)->format($format);
            } catch (Throwable $t) {
                $result = $t->getMessage();
            }
        } else {
            try {
                $result = date($format, $value);
            } catch (Throwable $t) {
                $result = $t->getMessage();
            }
        }

        return $result;
    }

    public function dateNoYear($value)
    {
        if ($value === null) {
            return '<span class="text-muted">-</span>';
        }

        $format = 'j M';
        if ($value instanceof DateTimeInterface) {
            return $value->format($format);
        }

        if (is_string($value)) {
            return DateUtils::create($value)->format($format);
        }

        return date($format, $value);
    }
}
