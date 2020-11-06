<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;


use PHPUnit\Framework\TestCase;
use Signaturit\Cesc\Application\Exception\EmptySignatureSideIsAlreadyWinningException;
use Signaturit\Cesc\Application\Exception\NoSideHasEmptySignatureException;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;
use Signaturit\Cesc\Infrastructure\Persistence\SignatureRepository;
use Signaturit\Cesc\Stubs\Domain\Contract\ContractStub;
use Signaturit\Cesc\Stubs\Domain\Signature\SignatureStub;

class CalculateMinimumSignatureServiceTest extends TestCase
{
    /**@var CalculateMinimumSignatureService */
    private $calculateMinimumSignatureService;

    public function testNoEmptySide(): void
    {
        $this->prepare([SignatureStub::validator()], [SignatureStub::validator()]);
        self::expectException(NoSideHasEmptySignatureException::class);
        $this->calculateMinimumSignatureService->execute("");
    }

    public function testAlreadyWinning(): void
    {
        $this->prepare([SignatureStub::validator(), SignatureStub::empty()], [SignatureStub::validator()]);
        self::expectException(EmptySignatureSideIsAlreadyWinningException::class);
        $this->calculateMinimumSignatureService->execute("");
    }

    public function testNotEventKing(): void
    {
        $this->prepare([
            SignatureStub::validator(),
            SignatureStub::validator(),
            SignatureStub::empty()
        ], [
            SignatureStub::king()
        ]);
        self::expectException(SignatureNotFoundException::class);
        $this->calculateMinimumSignatureService->execute("");
    }

    public function testDontEventThinkYouCanWin(): void
    {
        $this->prepare([
            SignatureStub::king(),
            SignatureStub::empty()
        ], [
            SignatureStub::king(),
            SignatureStub::king(),
            SignatureStub::king(),
            SignatureStub::king()
        ]);
        self::expectException(SignatureNotFoundException::class);
        $this->calculateMinimumSignatureService->execute("");
    }

    public function testSuccess(): void
    {
        $this->prepare([
            SignatureStub::king(),
            SignatureStub::empty()
        ], [
            SignatureStub::king(),
            SignatureStub::notary()
        ]);

        $result = $this->calculateMinimumSignatureService->execute("");
        self::assertEquals("N", $result);
    }


    private function prepare(array $plaintiffSigns, array $defendantSigns): void
    {
        $generateContractService = self::createMock(GenerateContractService::class);

        $contract = ContractStub::create($plaintiffSigns, $defendantSigns);
        $generateContractService->method("execute")->willReturn($contract);

        $signatureRepository = self::createMock(SignatureRepository::class);
        $signatureRepository->method("getSmallerSignatureBiggerThan")->willReturn(SignatureStub::notary());


        $this->calculateMinimumSignatureService = new CalculateMinimumSignatureService(
            $generateContractService,
            $signatureRepository
        );
    }


}
