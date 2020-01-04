<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\ArrayExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class ArrayExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $ext = new ArrayExtension();

        $filter = $ext->getFilters()[0];

        $this->assertInstanceOf(TwigFilter::class, $filter);
    }

    public function testMaxValue(): void
    {
        $ext = new ArrayExtension();

        $items = [
            ['k1' => 3, 'k2' => 10,],
            ['k1' => 5, 'k2' => 100,],
            ['k1' => -10,],
        ];

        $this->assertSame(5, $ext->maxValue($items, 'k1'));
    }

    public function testArraySum(): void
    {
        $ext = new ArrayExtension();

        $items = [3, 5, -10];

        $this->assertSame(-2, $ext->arraySum($items));
    }

    public function testSumValue(): void
    {
        $ext = new ArrayExtension();

        $items = [
            ['k1' => 3, 'k2' => 10,],
            ['k1' => 5, 'k2' => 100,],
            ['k1' => -10,],
        ];

        $this->assertSame(-2, $ext->sumValue($items, 'k1'));
    }

    public function testMaxRatioValue(): void
    {
        $ext = new ArrayExtension();

        $items = [
            ['k1' => 3, 'k2' => 30,],
            ['k1' => 5, 'k2' => 100,],
            ['k1' => 7, 'k2' => 14],
        ];

        $this->assertSame(0.5, $ext->maxRatioValue($items, 'k1', 'k2'));
    }

    public function testUniq(): void
    {
        $ext = new ArrayExtension();

        $items = [5, -1, 12, 5, 7, -1];

        $this->assertSame([5, -1, 12, 7], $ext->uniq($items));
    }
}
