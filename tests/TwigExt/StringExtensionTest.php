<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\StringExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class StringExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $ext = new StringExtension();

        $filter = $ext->getFilters()[0];

        $this->assertInstanceOf(TwigFilter::class, $filter);
    }
}
