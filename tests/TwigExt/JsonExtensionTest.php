<?php

namespace Gupalo\Tests\TwigExt;

use Gupalo\TwigExt\JsonExtension;
use PHPUnit\Framework\TestCase;

class JsonExtensionTest extends TestCase
{
    public function testJsonDecode(): void
    {
        $extension = new JsonExtension();

        self::assertSame(['k' => 'v'], $extension->jsonDecode('{"k":"v"}'));
    }
}
