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

    public function testAddSignatures(): void
    {
        $contract = new Contract([], []);

        $signature = SignatureStub::king();
        $signature2 = SignatureStub::validator();

        $contract->addPlaintiffSignature($signature);
        $contract->addDefendantSignature($signature2);

        self::assertEquals($signature, $contract->getPlaintiffSignatures()[0]);
        self::assertEquals($signature2, $contract->getDefendantSignatures()[0]);
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

    public function testGetEmptySignatureSide(): void
    {
        $contract = new Contract(
            [
                SignatureStub::empty(),
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

        self::assertEquals(Contract::PLAINTIFF_SIDE, $contract->getEmptySignatureSide());

        $contract = new Contract(
            [
                SignatureStub::validator(),
                SignatureStub::notary(),
                SignatureStub::king(),
                SignatureStub::validator(),
            ],
            [
                SignatureStub::empty(),
                SignatureStub::king(),
                SignatureStub::validator(),
                SignatureStub::king(),
            ],
        );

        self::assertEquals(Contract::DEFENDANT_SIDE, $contract->getEmptySignatureSide());
    }


}
