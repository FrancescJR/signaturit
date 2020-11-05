<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Contract\Contract;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Domain\Signature\Signature;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureValue;
use Signaturit\Cesc\Stubs\Domain\Contract\ContractStub;
use Signaturit\Cesc\Stubs\Domain\Signature\SignatureStub;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureRoleStub;
use Signaturit\Cesc\Stubs\Domain\Signature\ValueObject\SignatureValueStub;

class PerformTrialServiceTest extends TestCase
{
    private $generateContractService;

    public function testSuccess(): void
    {
        $this->prepare([5, 4, 3, 2], [3, 4, 5, 6]);
        $performTrialService = new performTrialService($this->generateContractService);

        self::assertEquals(PerformTrialService::DEFENDANT_WINS, $performTrialService->execute(""));

        $this->prepare([5, 4, 3, 2], [3, 6]);
        $performTrialService = new performTrialService($this->generateContractService);

        self::assertEquals(PerformTrialService::PLAINTIFF_WINS, $performTrialService->execute(""));
    }

    public function prepare(array $plaintiffSignaturesNumber, array $defendantSignaturesNumber): void
    {
        $this->generateContractService = self::createMock(GenerateContractService::class);

        $plaintiffSignatures = [];
        $defendantSignatures = [];

        // stubs would be nice here
        foreach ($plaintiffSignaturesNumber as $number) {
            $plaintiffSignatures[] = SignatureStub::create(
                SignatureRoleStub::default(),
                SignatureValueStub::withValue($number)
            );
        }

        foreach ($defendantSignaturesNumber as $number) {
            $defendantSignatures[] = SignatureStub::create(
                SignatureRoleStub::default(),
                SignatureValueStub::withValue($number)
            );
        }

        $contract = ContractStub::create(
            $plaintiffSignatures,
            $defendantSignatures
        );

        $this->generateContractService->method("execute")->willReturn($contract);
    }


}
