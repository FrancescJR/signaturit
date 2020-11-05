<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Signature\ValueObject;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;

class SignatureRoleTest extends TestCase
{
    public function testInvalid(): void
    {
        self::expectException(InvalidSignatureRoleValueException::class);

        new SignatureRole("A");
    }

    public function testGoodRoles(): void
    {
        foreach (SignatureRole::ROLES as $role) {
            $signatureRole = new SignatureRole($role);
            self::assertEquals($signatureRole->value(), $role);
        }
    }

}
