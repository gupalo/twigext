<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\EncodingExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class EncodingExtensionTest extends TestCase
{
    private EncodingExtension $ext;

    protected function setUp(): void
    {
        $this->ext = new EncodingExtension();
    }

    public function testGetFilters(): void
    {
        $filters = $this->ext->getFilters();

        self::assertCount(3, $filters);
        self::assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    public function testGetFunctions(): void
    {
        $functions = $this->ext->getFunctions();

        self::assertCount(3, $functions);
    }
}
