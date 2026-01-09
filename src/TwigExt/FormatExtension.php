<?php /** @noinspection PhpMethodMayBeStaticInspection */

namespace Gupalo\TwigExt;

use Gupalo\DateUtils\DateUtils;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormatExtension extends AbstractExtension
{
    private const array DEFAULT_TRANSLATIONS = [
        'span' => 'span',
        'text-muted' => 'text-muted',
        'text-danger' => 'text-danger',
        'ERR' => 'ERR',
        '-' => '-',
        'Y-m-d' => 'Y-m-d',
        'Y-m-d H:i:s' => 'Y-m-d H:i:s',
        'j M' => 'j M',
        'format-dot' => 'format-dot',
        'format-fractional' => 'format-fractional',
        // custom
        'format-zero' => 'text-muted',
        'format-negative-percents' => 'text-danger',
        'format-dot-zero' => 'text-muted',
        'format-date-zero' => 'text-muted',
        'format-fractional-zero' => 'text-muted',
        'format-percent-sign' => '',
    ];
    private const array NUMBER_THOUSAND_SEPARATORS = [',', ' ', '_'];

    private array $translations;

    private bool $wrapSpan;

    public function __construct(array $translations = [], bool $wrapSpan = true)
    {
        $this->translations = $translations;
        $this->wrapSpan = $wrapSpan;
    }

    /** @noinspection PhpArgumentWithoutNamedIdentifierInspection */
    public function getFilters(): array
    {
        return [
            new TwigFilter('int', $this->int(...), ['is_safe' => ['html']]),
            new TwigFilter('float', $this->float(...), ['is_safe' => ['html']]),
            new TwigFilter('money', $this->money(...), ['is_safe' => ['html']]),
            new TwigFilter('percents', $this->percents(...), ['is_safe' => ['html']]),
            new TwigFilter('date_full', $this->dateFull(...), ['is_safe' => ['html']]),
            new TwigFilter('date_short', $this->dateShort(...), ['is_safe' => ['html']]),
            new TwigFilter('date_noyear', $this->dateNoYear(...), ['is_safe' => ['html']]),
        ];
    }

    public function int($value = null): string
    {
        $value = $this->numberFromString($value);
        $result = $this->getSpecialNumberResult($value);

        return $result ?? number_format((float)$value);
    }

    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    public function float($value = null, int $precision = 2): string
    {
        $value = $this->numberFromString($value);
        $result = $this->getSpecialNumberResult($value);
        if ($result !== null) {
            return $result;
        }

        $result = number_format((float)$value, $precision);
        $result = $this->formatFractionalPart($result);

        return $result;
    }

    public function money($value = null, string $currency = '', int $precision = 2): string
    {
        $value = $this->numberFromString($value);
        $result = $this->getSpecialNumberResult($value);
        if ($result !== null) {
            return $result;
        }

        $negativeSign = ($value < 0) ? '-' : '';
        $value = abs($value);

        $result = number_format((float)$value, $precision);
        $result = $this->formatFractionalPart($result);

        $result = preg_replace('#\.\d+$#', '<span class="text-muted">$0</span>', $result);

        if (in_array($currency, ['', '$', '€', '₽', '₴', '£', '&dollar;', '&euro;', '&pound;', '＄', '﹩'], true) || preg_match('#^&\#[xa-fA-F\d]+;$#', $currency)) {
            $result = implode('', [$negativeSign, $currency, $result]);
        } elseif (preg_match('#^[A-Z]{3}$#', $currency)) {
            $result = implode('', [$currency, ' ', $negativeSign, $result]);
        } else {
            $result = implode('', [$negativeSign, $result, ' ', $currency]);
        }

        return $result;
    }

    public function percents($value = null, $precision = 2): string
    {
        $result = $this->getNullErrResult($value);
        if ($result !== null) {
            return $result;
        }

        $class = null;
        if ($this->isAlmostZero($value)) {
            $class = $this->trans('format-zero');
        } elseif ($value < 0) {
            $class = $this->trans('format-negative-percents');
        }

        $result = number_format(100 * (float)$value, $precision);
        $result = $this->formatFractionalPart($result) . $this->spanClass('%', $this->trans('format-percent-sign'));

        if ($class) {
            $result = $this->spanClass($result, $class);
        }

        return $result;
    }

    public function dateFull($value): string
    {
        return $this->dateFormat($value, DateUtils::FORMAT_FULL);
    }

    public function dateShort($value): string
    {
        return $this->dateFormat($value, DateUtils::FORMAT_SHORT);
    }

    public function dateNoYear($value): string
    {
        return $this->dateFormat($value, 'j M');
    }

    private function dateFormat($value, string $format = DateUtils::FORMAT_FULL): string
    {
        if ($value) {
            $value = DateUtils::create($value);
        }

        if ($value === null || $value <= DateUtils::create('1970-01-01')) {
            return $this->spanClass('-', 'format-date-zero');
        }

        return $value->format($this->trans($format));
    }

    private function isAlmostZero($value): bool
    {
        return ($value > -0.0001 && $value < 0.0001);
    }

    protected function numberFromString($value)
    {
        if (is_string($value)) {
            $value = str_replace(self::NUMBER_THOUSAND_SEPARATORS, '', $value);
        }

        return $value;
    }

    private function getSpecialNumberResult($value): ?string
    {
        if ($value === null) {
            return $this->spanMuted('-');
        }
        if (is_array($value)) {
            return $this->trans('ERR');
        }

        if ($this->isAlmostZero($value)) {
            return $this->spanClass('0', 'format-zero');
        }

        return null;
    }

    private function getNullErrResult($value): ?string
    {
        if ($value === null) {
            return $this->spanMuted('-');
        }
        if (is_array($value)) {
            return $this->trans('ERR');
        }

        return null;
    }

    private function spanMuted(string $s): string
    {
        return $this->spanClass($s, 'text-muted');
    }

    private function spanClass(string $s, string $class): string
    {
        if (!$this->wrapSpan || $class === '') {
            return $s;
        }

        return sprintf('<%s class="%s">%s</%s>', $this->trans('span'), $this->trans($class), $s, $this->trans('span'));
    }

    private function trans(string $s): string
    {
        return $this->translations[$s] ?? self::DEFAULT_TRANSLATIONS[$s] ?? $s;
    }

    protected function formatFractionalPart(string $s): string
    {
        if (preg_match('#^(-?[\d,]+)(\.)(\d+)$#', $s, $m)) {
            $classDotArray[] = $this->trans('format-dot');
            if (preg_match('#^0+$#', $m[3])) {
                $classDotArray[] = $this->trans('format-dot-zero');
            }
            $classDot = implode(' ', array_unique($classDotArray));

            $classFractionalArray[] = $this->trans('format-fractional');
            if (preg_match('#^0+$#', $m[3])) {
                $classFractionalArray[] = $this->trans('format-fractional-zero');
            }
            $classFractional = implode(' ', array_unique($classFractionalArray));

            $s = implode('', [$m[1], $this->spanClass($m[2], $classDot), $this->spanClass($m[3], $classFractional)]);
        }

        return $s;
    }
}
