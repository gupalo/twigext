<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\ProgressExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class ProgressExtensionTest extends TestCase
{
    private ProgressExtension $ext;

    protected function setUp(): void
    {
        $this->ext = new ProgressExtension();
    }

    public function testGetFilters(): void
    {
        $filters = $this->ext->getFilters();

        self::assertCount(4, $filters);
        self::assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    public function testProgressClass(): void
    {
        $result = $this->ext->progressClass(50, 100);

        self::assertStringContainsString('pie-progress', $result);
        self::assertStringContainsString('pie-progress-share50', $result);
    }

    public function testProgressClassWithColor(): void
    {
        $result = $this->ext->progressClass(25, 100, 'red');

        self::assertStringContainsString('pie-progress-red', $result);
    }

    public function testProgressClassZeroMaxValue(): void
    {
        $result = $this->ext->progressClass(50, 0);

        self::assertSame('', $result);
    }

    public function testProgressPercents(): void
    {
        $result = $this->ext->progressPercents(0.5, 1);

        self::assertStringContainsString('50.00%', $result);
        self::assertStringContainsString('<div class="bar"', $result);
    }

    public function testProgressPercentsZeroValue(): void
    {
        $result = $this->ext->progressPercents(0.0, 1);

        self::assertSame('', $result);
    }

    public function testProgressInt(): void
    {
        $result = $this->ext->progressInt(50, 100);

        self::assertStringContainsString('50', $result);
        self::assertStringContainsString('<div class="bar"', $result);
    }

    public function testProgressIntNullValue(): void
    {
        $result = $this->ext->progressInt(null, 100);

        self::assertSame('', $result);
    }

    public function testProgressFloat(): void
    {
        $result = $this->ext->progressFloat(50.5, 100);

        self::assertStringContainsString('50.50', $result);
        self::assertStringContainsString('<div class="bar"', $result);
    }

    public function testProgressFloatWithPrecision(): void
    {
        $result = $this->ext->progressFloat(50.123, 100, 3);

        self::assertStringContainsString('50.123', $result);
    }
}
