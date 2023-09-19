<?php

namespace Gupalo\Tests\TwigExt;

use Generator;
use Gupalo\TwigExt\RandomExtension;
use PHPUnit\Framework\TestCase;

class RandomExtensionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->ext = new RandomExtension();
    }

    public function testRandomDay(): void
    {
        $random = $this->ext->randomDay(10);
        self::assertGreaterThan(1, $random);
        self::assertLessThanOrEqual(10, $random);
        self::assertSame($random, $this->ext->randomDay(10));

        // same if run multiple times same day with the same salt
        self::assertSame($random, $this->ext->randomDay(10));

        // cannot divide by zero; return zero
        self::assertSame(0, $this->ext->randomDay(0));

        // only positive values make sense in "max" param; return zero
        self::assertSame(0, $this->ext->randomDay(-100));
    }

    public function testRandomHour(): void
    {
        $random = $this->ext->randomHour(10);
        self::assertGreaterThan(1, $random);
        self::assertLessThanOrEqual(10, $random);
        self::assertSame($random, $this->ext->randomHour(10));

        // same if run multiple times same day with the same salt
        self::assertSame($random, $this->ext->randomHour(10));


        // cannot divide by zero; return zero
        self::assertSame(0, $this->ext->randomHour(0));

        // only positive values make sense in "max" param; return zero
        self::assertSame(0, $this->ext->randomHour(-100));
    }

    public function testPick(): void
    {
        self::assertNull($this->ext->randomItem([]));
        self::assertTrue($this->ext->randomItem([true]));
        self::assertSame(1, $this->ext->randomItem([1]));
        self::assertSame('1', $this->ext->randomItem(['1']));
        self::assertSame('1', $this->ext->randomItem(['1', '1', '1']));
        self::assertContains($this->ext->randomItem(['a', 'b', 'c']), ['a', 'b', 'c']);
    }

    public function testPickMultiple(): void
    {
        self::assertSame([], $this->ext->randomItems([]));
        self::assertNull($this->ext->randomItems([], default: null));
        self::assertSame([true], $this->ext->randomItems([true]));
        self::assertSame([1], $this->ext->randomItems([1]));
        self::assertSame(['1'], $this->ext->randomItems(['1']));
        self::assertSame(['1'], $this->ext->randomItems(['1', '1', '1'], preserveKeys: false));
        self::assertContains($this->ext->randomItems(['a', 'b', 'c']), [['a'], ['b'], ['c']]);

        self::assertContains($this->ext->randomItems(['a', 'b'], count: 2), [['a', 'b'], ['b', 'a']]);
        self::assertContains($this->ext->randomItems($this->generator(), count: 2), [['a', 'b'], ['b', 'a']]);
        self::assertContains($this->ext->randomItems(['k1' => 'a', 'k2' => 'b'], count: 2), [['a', 'b'], ['b', 'a']]);
        self::assertContains($this->ext->randomItems(['k1' => 'a', 'k2' => 'b'], count: 2, preserveKeys: true), [['k1' => 'a', 'k2' => 'b'], ['k2' => 'b', 'k1' => 'a']]);

        self::assertContains($this->ext->randomItems(['k1' => 'a', 'k2' => 'b'], count: 100, preserveKeys: true), [['k1' => 'a', 'k2' => 'b'], ['k2' => 'b', 'k1' => 'a']]);
    }

    private function generator(): Generator
    {
        yield 'a';
        yield 'b';
    }
}
