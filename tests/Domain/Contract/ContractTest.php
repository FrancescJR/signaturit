<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Contract;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Stubs\Domain\Signature\SignatureStub;


class ContractTest extends TestCase
{

    public function testConstructAndTheGetters(): void
    {
        // Doing it via stubs would give it more sense
        $signature = SignatureStub::default();

        $contract = new Contract(
            [$signature],
            [$signature]
        );

        self::assertEquals($signature, $contract->getDefendantSignatures()[0]);
        self::assertEquals($signature, $contract->getPlaintiffSignatures()[0]);
    }

    public function testScore()
    {
        $contract = new Contract(
            [
                SignatureStub::validator(),
                SignatureStub::validator(),
                SignatureStub::validator(),
                SignatureStub::validator(),
            ],
            [
                SignatureStub::validator(),
                SignatureStub::validator(),
                SignatureStub::validator(),
                SignatureStub::validator(),
            ],
        );

        self::assertEquals(4, $contract->getPlaintiffScore());
        self::assertEquals(4, $contract->getDefendantScore());

    }

    public function testKingNullsValidatorScore()
    {
        $contract = new Contract(
            [
                SignatureStub::validator(),
                SignatureStub::notary(),
                SignatureStub::king(),
                SignatureStub::validator(),
            ],
            [
                SignatureStub::validator(),
                SignatureStub::king(),
                SignatureStub::validator(),
                SignatureStub::king(),
            ],
        );

        self::assertEquals(7, $contract->getPlaintiffScore());
        self::assertEquals(10, $contract->getDefendantScore());
    }

}
