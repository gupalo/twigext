<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\StringExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class StringExtensionTest extends TestCase
{
    private StringExtension $ext;

    protected function setUp(): void
    {
        $this->ext = new StringExtension();
    }

    public function testGetFilters(): void
    {
        $filter = $this->ext->getFilters()[0];

        self::assertInstanceOf(TwigFilter::class, $filter);
    }
}
