<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Stubs\Domain\Contract;

use Signaturit\Cesc\Domain\Contract\Contract;
use Signaturit\Cesc\Stubs\Domain\Signature\SignatureStub;

class ContractStub
{
    public static function create(array $plaintiff, array $defendants): Contract
    {
        return new Contract($plaintiff, $defendants);
    }

    public static function default(): Contract
    {
        return static::create([SignatureStub::default()], [SignatureStub::default()]);
    }

}
