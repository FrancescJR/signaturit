<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;
use Signaturit\Cesc\Stubs\Domain\Contract\ContractStub;
use Signaturit\Cesc\Stubs\Domain\Signature\SignatureStub;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureRoleStub;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureValueStub;

class PerformTrialServiceTest extends TestCase
{
    private $generateContractService;

    public function testSuccess(): void
    {
        $this->prepare([
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_VALIDATOR,
        ], [
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_NOTARY,
            SignatureRole::ROLE_NOTARY,
        ]);
        $performTrialService = new performTrialService($this->generateContractService);

        self::assertEquals(PerformTrialService::DEFENDANT_WINS, $performTrialService->execute(""));
    }

    public function testKingNullsValidators(): void
    {
        $this->prepare([
            SignatureRole::ROLE_NOTARY,
            SignatureRole::ROLE_NOTARY,
            SignatureRole::ROLE_NOTARY,
            SignatureRole::ROLE_NOTARY,
        ], [
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_VALIDATOR,
            SignatureRole::ROLE_KING,
        ]);
        $performTrialService = new performTrialService($this->generateContractService);

        self::assertEquals(PerformTrialService::PLAINTIFF_WINS, $performTrialService->execute(""));
    }

    private function prepare(array $plaintiffSignaturesNumber, array $defendantSignaturesNumber): void
    {
        $this->generateContractService = self::createMock(GenerateContractService::class);

        $contract = ContractStub::create(
            $this->generateSignatureList($plaintiffSignaturesNumber),
            $this->generateSignatureList($defendantSignaturesNumber)
        );

        $this->generateContractService->method("execute")->willReturn($contract);
    }

    private function generateSignatureList(array $initials): array
    {
        $signatures = [];
        foreach ($initials as $initial) {
            switch ($initial) {
                case SignatureRole::ROLE_KING:
                    $signatures[] = SignatureStub::king();
                    break;
                case SignatureRole::ROLE_NOTARY:
                    $signatures[] = SignatureStub::notary();
                    break;
                case SignatureRole::ROLE_VALIDATOR:
                    $signatures[] = SignatureStub::validator();
                    break;
            }
        }
        return $signatures;
    }

}
