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


}
