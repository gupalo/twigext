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
        $filters = $this->ext->getFilters();

        self::assertCount(6, $filters);
        self::assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    public function testTruncate(): void
    {
        self::assertSame('hello', $this->ext->truncate('hello', 10));
        self::assertSame('hel&hellip;', $this->ext->truncate('hello world', 3));
    }

    public function testTruncateNull(): void
    {
        self::assertSame('', $this->ext->truncate(null));
    }

    public function testTruncateHtml(): void
    {
        self::assertSame('hello', $this->ext->truncateHtml('hello', 10));
        self::assertSame('hel&hellip;', $this->ext->truncateHtml('hello world', 3));
    }

    public function testTruncateHtmlEscapes(): void
    {
        self::assertSame('&lt;di&hellip;', $this->ext->truncateHtml('<div>test</div>', 3));
    }

    public function testUnderscore(): void
    {
        self::assertSame('camel_case? ok', $this->ext->underscore('CamelCase? ok'));
    }

    public function testUnderscoreWithCustomCharacter(): void
    {
        self::assertSame('camel-case', $this->ext->underscore('CamelCase', '-'));
    }

    public function testUnderscoreNull(): void
    {
        self::assertSame('', $this->ext->underscore(null));
    }

    public function testMaskPassword(): void
    {
        self::assertSame('myS*****', $this->ext->maskPassword('mySuperPassword'));
    }

    public function testMaskPasswordShort(): void
    {
        self::assertSame('ab******', $this->ext->maskPassword('ab'));
    }

    public function testMaskPasswordNull(): void
    {
        self::assertSame('********', $this->ext->maskPassword(null));
    }

    public function testSafeTitle(): void
    {
        self::assertSame("it's a 'test'", $this->ext->safeTitle('it\'s a "test"'));
    }

    public function testSafeTitleNull(): void
    {
        self::assertSame('', $this->ext->safeTitle(null));
    }

    public function testUcfirst(): void
    {
        self::assertSame('Hello', $this->ext->ucfirst('hello'));
    }

    public function testUcfirstNull(): void
    {
        self::assertSame('', $this->ext->ucfirst(null));
    }
}
