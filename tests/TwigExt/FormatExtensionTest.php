<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\FormatExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class FormatExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $ext = new FormatExtension();

        $filter = $ext->getFilters()[0];

        $this->assertInstanceOf(TwigFilter::class, $filter);
    }
}
