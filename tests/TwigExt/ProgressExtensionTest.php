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
        $filter = $this->ext->getFilters()[0];

        self::assertInstanceOf(TwigFilter::class, $filter);
    }
}
