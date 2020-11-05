<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Contract;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;
use Signaturit\Cesc\Stubs\Domain\Signature\SignatureStub;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureRoleStub;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureValueStub;


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

    public function testSorting(): void
    {
        // Doing it via stubs would give it more sense
        $signature = SignatureStub::default();

        $contract = new Contract(
            [
                SignatureStub::create(
                    SignatureRoleStub::default(), SignatureValueStub::default()
                ),
                SignatureStub::create(
                    SignatureRoleStub::withValue(SignatureRole::ROLE_NOTARY), SignatureValueStub::default()
                ),
                SignatureStub::create(
                    SignatureRoleStub::withValue(SignatureRole::ROLE_KING), SignatureValueStub::default()
                ),
                SignatureStub::create(
                    SignatureRoleStub::default(), SignatureValueStub::default()
                ),
            ],
            [
                SignatureStub::create(
                    SignatureRoleStub::default(), SignatureValueStub::default()
                ),
                SignatureStub::create(
                    SignatureRoleStub::withValue(SignatureRole::ROLE_KING), SignatureValueStub::default()
                ),
                SignatureStub::create(
                    SignatureRoleStub::default(), SignatureValueStub::default()
                ),
                SignatureStub::create(
                    SignatureRoleStub::withValue(SignatureRole::ROLE_KING), SignatureValueStub::default()
                ),
            ],
        );

        self::assertEquals(SignatureRole::ROLE_KING, $contract->getDefendantSignatures()[0]->getRole()->value());
        self::assertEquals(SignatureRole::ROLE_KING, $contract->getPlaintiffSignatures()[0]->getRole()->value());
        self::assertCount(4, $contract->getDefendantSignatures());
        self::assertCount(4, $contract->getPlaintiffSignatures());
    }

}
