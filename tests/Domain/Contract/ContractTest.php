<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Contract;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Signature\Signature;


class ContractTest extends TestCase
{

    public function testConstructAndTheGetter(): void
    {
        // Doing it via stubs would give it more sense
        $signature = self::createMock(Signature::class);

        $contract = new Contract(
            [$signature],
            [$signature]
        );

        self::assertEquals($signature, $contract->getDefendantSignatures()[0]);
        self::assertEquals($signature, $contract->getPlaintiffSignatures()[0]);
    }

}
