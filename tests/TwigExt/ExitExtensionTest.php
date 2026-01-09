<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\ExitExtension;
use Gupalo\TwigExtException\TwigExitException;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class ExitExtensionTest extends TestCase
{
    private ExitExtension $ext;

    protected function setUp(): void
    {
        $this->ext = new ExitExtension();
    }

    public function testGetFunctions(): void
    {
        $functions = $this->ext->getFunctions();

        self::assertCount(1, $functions);
        self::assertInstanceOf(TwigFunction::class, $functions[0]);
    }

    public function testExitThrowsException(): void
    {
        $this->expectException(TwigExitException::class);
        $this->expectExceptionCode(500);

        $this->ext->exit();
    }

    public function testExitWithCustomStatusCode(): void
    {
        $this->expectException(TwigExitException::class);
        $this->expectExceptionCode(404);

        $this->ext->exit(404, 'Not found');
    }
}
