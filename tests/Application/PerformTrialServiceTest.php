<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;

use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Domain\Contract\Contract;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Domain\Signature\Signature;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureValue;

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
            $signatureValue = self::createMock(SignatureValue::class);
            $signatureValue->method('value')->willReturn($number);
            $signature = self::createMock(Signature::class);
            $signature->method('getValue')->willReturn($signatureValue);
            $plaintiffSignatures[] = $signature;
        }

        foreach ($defendantSignaturesNumber as $number) {
            $signatureValue = self::createMock(SignatureValue::class);
            $signatureValue->method('value')->willReturn($number);
            $signature = self::createMock(Signature::class);
            $signature->method('getValue')->willReturn($signatureValue);
            $defendantSignatures[] = $signature;
        }

        $contract = self::createMock(Contract::class);
        $contract->method('getPlaintiffSignatures')->willReturn($plaintiffSignatures);
        $contract->method('getDefendantSignatures')->willReturn($defendantSignatures);

        $this->generateContractService->method("execute")->willReturn($contract);
    }


}
