<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Signature;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureValue;

class SignatureTest extends TestCase
{
    public function testConstructAndTheGetter(): void
    {
        // Doing it via stubs would give it more sense
        $signatureRole  = self::createMock(SignatureRole::class);
        $signatureValue = self::createMock(SignatureValue::class);
        $signature      = new Signature(
            $signatureRole,
            $signatureValue
        );

        self::assertEquals($signatureValue, $signature->getValue());
    }

}
