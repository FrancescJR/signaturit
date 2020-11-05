<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Stubs\Domain\Signature\ValueObject;

use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureValue;

class SignatureValueStub
{
    public const DEFAULT_VALUE = 1;

    public static function default(): SignatureValue
    {
        return new SignatureValue(self::DEFAULT_VALUE);
    }

    public static function withValue(int $value): SignatureValue
    {
        return new SignatureValue($value);
    }

}
