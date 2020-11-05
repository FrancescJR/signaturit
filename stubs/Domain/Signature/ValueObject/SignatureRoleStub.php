<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Stubs\Domain\Signature\ValueObject;


use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;

class SignatureRoleStub
{
    public const DEFAULT_VALUE = "V";

    public static function default(): SignatureRole
    {
        return new SignatureRole(self::DEFAULT_VALUE);
    }

    /**
     * @param string $value
     *
     * @return SignatureRole
     * @throws InvalidSignatureRoleValueException
     */
    public static function withValue(string $value): SignatureRole
    {
        return new SignatureRole($value);
    }

}
