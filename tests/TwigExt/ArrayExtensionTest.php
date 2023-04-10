<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\ArrayExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class ArrayExtensionTest extends TestCase
{
    private ArrayExtension $ext;

    protected function setUp(): void
    {
        $this->ext = new ArrayExtension();
    }

    public function testGetFilters(): void
    {
        $filter = $this->ext->getFilters()[0];

        self::assertInstanceOf(TwigFilter::class, $filter);
    }

    public function testMaxValue(): void
    {
        $items = [
            ['k1' => 3, 'k2' => 10,],
            ['k1' => 5, 'k2' => 100,],
            ['k1' => -10,],
        ];

        self::assertSame(5, $this->ext->maxValue($items, 'k1'));
    }

    public function testArraySum(): void
    {
        $items = [3, 5, -10];

        self::assertSame(-2, $this->ext->arraySum($items));
    }

    public function testSumValue(): void
    {
        $items = [
            ['k1' => 3, 'k2' => 10,],
            ['k1' => 5, 'k2' => 100,],
            ['k1' => -10,],
        ];

        self::assertSame(-2, $this->ext->sumValue($items, 'k1'));
    }

    public function testMaxRatioValue(): void
    {
        $items = [
            ['k1' => 3, 'k2' => 30,],
            ['k1' => 5, 'k2' => 100,],
            ['k1' => 7, 'k2' => 14],
        ];

        self::assertSame(0.5, $this->ext->maxRatioValue($items, 'k1', 'k2'));
    }

    public function testUniq(): void
    {
        $items = [5, -1, 12, 5, 7, -1];

        self::assertSame([5, -1, 12, 7], $this->ext->uniq($items));
    }
}
