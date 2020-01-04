<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\ProgressExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class ProgressExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $ext = new ProgressExtension();

        $filter = $ext->getFilters()[0];

        $this->assertInstanceOf(TwigFilter::class, $filter);
    }
}
