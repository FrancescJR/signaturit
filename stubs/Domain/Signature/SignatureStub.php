<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Stubs\Domain\Signature;

use Signaturit\Cesc\Domain\Signature\Signature;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureValue;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureRoleStub;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureValueStub;

class SignatureStub
{
    public static function create(SignatureRole $role, SignatureValue $value): Signature
    {
        return new Signature($role, $value);
    }

    public static function default(): Signature
    {
        return static::create(SignatureRoleStub::default(), SignatureValueStub::default());
    }

    public static function king(): Signature
    {
        return static::create(
            SignatureRoleStub::withValue(SignatureRole::ROLE_KING),
            SignatureValueStub::withValue(5)
        );
    }

    public static function notary(): Signature
    {
        return static::create(
            SignatureRoleStub::withValue(SignatureRole::ROLE_NOTARY),
            SignatureValueStub::withValue(2)
        );
    }

    public static function validator(): Signature
    {
        return static::create(
            SignatureRoleStub::withValue(SignatureRole::ROLE_VALIDATOR),
            SignatureValueStub::withValue(1)
        );
    }

    public static function empty(): Signature
    {
        return static::create(
            SignatureRoleStub::withValue(SignatureRole::ROLE_EMPTY),
            SignatureValueStub::withValue(0)
        );
    }


}
