<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Signature\ValueObject;

use PHPUnit\Framework\TestCase;

class SignatureValueTest extends TestCase
{
    public function testConstruct(): void
    {
        $valueInt       = 5;
        $valueSignature = new SignatureValue($valueInt);

        self::assertEquals($valueInt, $valueSignature->value());
    }

}
