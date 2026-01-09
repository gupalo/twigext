<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\JsonExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class JsonExtensionTest extends TestCase
{
    private JsonExtension $ext;

    protected function setUp(): void
    {
        $this->ext = new JsonExtension();
    }

    public function testGetFilters(): void
    {
        $filters = $this->ext->getFilters();

        self::assertCount(1, $filters);
        self::assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    public function testJsonDecode(): void
    {
        self::assertSame(['k' => 'v'], $this->ext->jsonDecode('{"k":"v"}'));
    }

    public function testJsonDecodeNull(): void
    {
        self::assertSame([], $this->ext->jsonDecode(null));
    }

    public function testJsonDecodeInvalidJson(): void
    {
        self::assertSame([], $this->ext->jsonDecode('invalid json'));
    }

    public function testJsonDecodeEmptyString(): void
    {
        self::assertSame([], $this->ext->jsonDecode(''));
    }
}
