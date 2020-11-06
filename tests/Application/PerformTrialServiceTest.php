<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Contract\Contract;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;

class PerformTrialServiceTest extends TestCase
{
    private $generateContractService;

    public function testSuccess(): void
    {
        $this->prepare(8,9);
        $performTrialService = new performTrialService($this->generateContractService);

        self::assertEquals(PerformTrialService::DEFENDANT_WINS, $performTrialService->execute(""));

        $this->prepare(8,7);
        $performTrialService = new performTrialService($this->generateContractService);

        self::assertEquals(PerformTrialService::PLAINTIFF_WINS, $performTrialService->execute(""));
    }

    public function testEqualClaims()
    {
        $this->prepare(8,8);
        $performTrialService = new performTrialService($this->generateContractService);

        self::assertEquals(PerformTrialService::EQUAL_CLAIMS, $performTrialService->execute(""));
    }



    private function prepare(int $plaintiffScore, int $defendantScore): void
    {
        $this->generateContractService = self::createMock(GenerateContractService::class);

        $contract = self::createMock(Contract::class);
        $contract->method("getPlaintiffScore")->willReturn($plaintiffScore);
        $contract->method("getDefendantScore")->willReturn($defendantScore);

        $this->generateContractService->method("execute")->willReturn($contract);
    }

}
