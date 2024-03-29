<?php

namespace Gupalo\Tests\TwigExt;

use DateTime;
use Gupalo\TwigExt\FormatExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class FormatExtensionTest extends TestCase
{
    private static FormatExtension $ext;

    protected function setUp(): void
    {
        self::$ext = new FormatExtension();
    }

    public function testGetFilters(): void
    {
        $filter = self::$ext->getFilters()[0];

        self::assertInstanceOf(TwigFilter::class, $filter);
    }

    #[DataProvider('providerInt')]
    public function testInt(mixed $value, string $expected): void
    {
        self::assertSame($expected, self::$ext->int($value));
    }

    public static function providerInt(): array
    {
        return [
            'zero'                                => [0,            '<span class="text-muted">0</span>'],
            'almost_zero'                         => [0.0000001,    '<span class="text-muted">0</span>'],
            'almost_zero_negative'                => [-0.0000001,   '<span class="text-muted">0</span>'],
            'normal_1'                            => [1,            '1'],
            'normal_2'                            => [100,          '100'],
            'thousand_separator'                  => [1000,         '1,000'],
            'multiple_thousand_separators'        => [1000000,      '1,000,000'],
            'negative_thousand_separator'         => [-1000,        '-1,000'],
            'fractional'                          => [1000.1,       '1,000'],
            'negative_fractional'                 => [-1000.1,      '-1,000'],
            'string_number'                       => ['1000000',    '1,000,000'],
            'string_number_comma_separator'       => ['1,000,000',  '1,000,000'],
            'string_number_space_separator'       => ['1 000 000',  '1,000,000'],
            'string_number_underscore_separator'  => ['1_000_000',  '1,000,000'],
            'error_array'                         => [[1],          'ERR'],
            'null'                                => [null,         '<span class="text-muted">-</span>'],
        ];
    }

    #[DataProvider('providerFloat')]
    public function testFloat(mixed $value, int $precision, string $expected): void
    {
        self::assertSame($expected, self::$ext->float($value, $precision));
    }

    public static function providerFloat(): array
    {
        return [
            'zero'                               => [0, 2,           '<span class="text-muted">0</span>'],
            'almost_zero'                        => [0.0000001, 2,   '<span class="text-muted">0</span>'],
            'almost_zero_negative'               => [-0.0000001, 2,  '<span class="text-muted">0</span>'],
            'normal_1'                           => [1, 2,           '1<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'normal_2'                           => [100, 2,         '100<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'thousand_separator'                 => [1000, 2,        '1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'multiple_thousand_separators'       => [1000000, 2,     '1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'negative_thousand_separator'        => [-1000, 2,       '-1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'fractional'                         => [1000.1, 2,      '1,000<span class="format-dot">.</span><span class="format-fractional">10</span>'],
            'negative_fractional'                => [-1000.1, 2,     '-1,000<span class="format-dot">.</span><span class="format-fractional">10</span>'],
            'string_number'                      => ['1000000', 2,   '1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'string_number_comma_separator'      => ['1,000,000', 2, '1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'string_number_space_separator'      => ['1 000 000', 2, '1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'string_number_underscore_separator' => ['1_000_000', 2, '1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'error_array'                        => [[1], 2,         'ERR'],
            'null'                               => [null, 2,        '<span class="text-muted">-</span>'],
            'precision_0'                        => [1000, 0,        '1,000'],
            'precision_5'                        => [1000, 5,        '1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00000</span>'],
        ];
    }

    #[DataProvider('providerMoney')]
    public function testMoney(mixed $value, mixed $currency, int $precision, string $expected): void
    {
        self::assertSame($expected, self::$ext->money($value, $currency, $precision));
    }

    public static function providerMoney(): array
    {
        return [
            'zero'                               => [0, '$', 2,           '<span class="text-muted">0</span>'],
            'almost_zero'                        => [0.0000001, '$', 2,   '<span class="text-muted">0</span>'],
            'almost_zero_negative'               => [-0.0000001, '$', 2,  '<span class="text-muted">0</span>'],
            'normal_1'                           => [1, '$', 2,           '$1<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'normal_2'                           => [100, '$', 2,         '$100<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'thousand_separator'                 => [1000, '$', 2,        '$1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'multiple_thousand_separators'       => [1000000, '$', 2,     '$1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'negative_thousand_separator'        => [-1000, '$', 2,       '-$1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'fractional'                         => [1000.1, '$', 2,      '$1,000<span class="format-dot">.</span><span class="format-fractional">10</span>'],
            'negative_fractional'                => [-1000.1, '$', 2,     '-$1,000<span class="format-dot">.</span><span class="format-fractional">10</span>'],
            'string_number'                      => ['1000000', '$', 2,   '$1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'string_number_comma_separator'      => ['1,000,000', '$', 2, '$1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'string_number_space_separator'      => ['1 000 000', '$', 2, '$1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'string_number_underscore_separator' => ['1_000_000', '$', 2, '$1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'error_array'                        => [[1], '$', 2,         'ERR'],
            'null'                               => [null, '$', 2,        '<span class="text-muted">-</span>'],
            'precision_0'                        => [1000, '$', 0,        '$1,000'],
            'precision_5'                        => [1000, '$', 5,        '$1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00000</span>'],
            'currency_euro_1'                    => [-1000, '€', 2,       '-€1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'currency_euro_2'                    => [-1000, '&euro;', 2,  '-&euro;1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'currency_usd'                       => [1000, 'USD', 2,      'USD 1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'currency_usd_negative'              => [-1000, 'USD', 2,      'USD -1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>'],
            'currency_other'                     => [-1000, 'зайчиков', 2, '-1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span> зайчиков'],
        ];
    }

    #[DataProvider('providerPercents')]
    public function testPercents(mixed $value, int $precision, string $expected): void
    {
        self::assertSame($expected, self::$ext->percents($value, $precision));
    }

    public static function providerPercents(): array
    {
        return [
            'zero'                               => [0, 2,           '<span class="text-muted">0<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%</span>'],
            'almost_zero'                        => [0.0000001, 2,   '<span class="text-muted">0<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%</span>'],
            'almost_zero_negative'               => [-0.0000001, 2,  '<span class="text-muted">0<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%</span>'],
            'normal_1'                           => [1/100, 2,       '1<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%'],
            'normal_2'                           => [1, 2,           '100<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%'],
            'thousand_separator'                 => [1000/100, 2,    '1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%'],
            'multiple_thousand_separators'       => [1000000/100, 2, '1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%'],
            'negative_thousand_separator'        => [-1000/100, 2,   '<span class="text-danger">-1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%</span>'],
            'fractional'                         => [1000.1/100, 2,  '1,000<span class="format-dot">.</span><span class="format-fractional">10</span>%'],
            'negative_fractional'                => [-1000.1/100, 2, '<span class="text-danger">-1,000<span class="format-dot">.</span><span class="format-fractional">10</span>%</span>'],
            'string_number'                      => ['10000', 2,     '1,000,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00</span>%'],
            'error_array'                        => [[1], 2,         'ERR'],
            'null'                               => [null, 2,        '<span class="text-muted">-</span>'],
            'precision_0'                        => [1000/100, 0,    '1,000%'],
            'precision_5'                        => [1000/100, 5,    '1,000<span class="format-dot text-muted">.</span><span class="format-fractional text-muted">00000</span>%'],
        ];
    }

    #[DataProvider('providerDateFull')]
    public function testDateFull(mixed $value, string $expected): void
    {
        self::assertSame($expected, self::$ext->dateFull($value));
    }

    public static function providerDateFull(): array
    {
        return [
            'ymd' => ['2020-01-01', '2020-01-01 00:00:00'],
            'ymd_hi' => ['2020-01-01 12:34', '2020-01-01 12:34:00'],
            'ymd_his' => ['2020-01-01 12:34:56', '2020-01-01 12:34:56'],
            'date_ymd' => [new DateTime('2020-01-01'), '2020-01-01 00:00:00'],
            'date_ymd_hi' => [new DateTime('2020-01-01 12:34'), '2020-01-01 12:34:00'],
            'date_ymd_his' => [new DateTime('2020-01-01 12:34:56'), '2020-01-01 12:34:56'],
            'old' => ['1950-01-01', '<span class="text-muted">-</span>'],
            'empty_1' => ['', '<span class="text-muted">-</span>'],
            'empty_2' => [null, '<span class="text-muted">-</span>'],
        ];
    }

    #[DataProvider('providerDateShort')]
    public function testDateShort(mixed $value, string $expected): void
    {
        self::assertSame($expected, self::$ext->dateShort($value));
    }

    public static function providerDateShort(): array
    {
        return [
            'ymd' => ['2020-01-01', '2020-01-01'],
            'ymd_hi' => ['2020-01-01 12:34', '2020-01-01'],
            'ymd_his' => ['2020-01-01 12:34:56', '2020-01-01'],
            'date_ymd' => [new DateTime('2020-01-01'), '2020-01-01'],
            'date_ymd_hi' => [new DateTime('2020-01-01 12:34'), '2020-01-01'],
            'date_ymd_his' => [new DateTime('2020-01-01 12:34:56'), '2020-01-01'],
            'old' => ['1950-01-01', '<span class="text-muted">-</span>'],
            'empty_1' => ['', '<span class="text-muted">-</span>'],
            'empty_2' => [null, '<span class="text-muted">-</span>'],
        ];
    }

    #[DataProvider('providerDateNoYear')]
    public static function testDateNoYear(mixed $value, string $expected): void
    {
        self::assertSame($expected, self::$ext->dateNoYear($value));
    }

    public static function providerDateNoYear(): array
    {
        return [
            'ymd' => ['2020-01-01', '1 Jan'],
            'ymd_hi' => ['2020-01-01 12:34', '1 Jan'],
            'ymd_his' => ['2020-01-01 12:34:56', '1 Jan'],
            'date_ymd' => [new DateTime('2020-01-01'), '1 Jan'],
            'date_ymd_hi' => [new DateTime('2020-01-01 12:34'), '1 Jan'],
            'date_ymd_his' => [new DateTime('2020-01-01 12:34:56'), '1 Jan'],
            'old' => ['1950-01-01', '<span class="text-muted">-</span>'],
            'empty_1' => ['', '<span class="text-muted">-</span>'],
            'empty_2' => [null, '<span class="text-muted">-</span>'],
        ];
    }
}
