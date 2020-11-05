<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Contract\Service;


use PHPUnit\Framework\TestCase;
use SignatureRepository;
use Signaturit\Cesc\Domain\Contract\Exception\InvalidContractFormatException;
use Signaturit\Cesc\Domain\Signature\Signature;

class GenerateContractServiceTest extends TestCase
{

    private $generateContractService;

    public function setUp(): void
    {
        $signatureRepository = self::createMock(SignatureRepository::class);
        $signatureRepository->method("findByRole")->willReturn(self::createMock(Signature::class));

        $this->generateContractService = new GenerateContractService(
            $signatureRepository
        );
    }

    public function testMissingVs(): void
    {
        self::expectException(InvalidContractFormatException::class);
        $this->generateContractService->execute("HEO");
    }

    public function testMultipleParties(): void
    {
        self::expectException(InvalidContractFormatException::class);
        $this->generateContractService->execute("KvsKvsK");
    }


    public function testSuccess(): void
    {
        $contract = $this->generateContractService->execute("KvsK");
        self::assertCount(1, $contract->getPlaintiffSignatures());
        self::assertCount(1, $contract->getDefendantSignatures());

        $contract = $this->generateContractService->execute("KKKKvsKKKKK");
        self::assertCount(4, $contract->getPlaintiffSignatures());
        self::assertCount(5, $contract->getDefendantSignatures());

        $contract = $this->generateContractService->execute("KVvsKVNV");
        self::assertCount(2, $contract->getPlaintiffSignatures());
        self::assertCount(4, $contract->getDefendantSignatures());

        $contract = $this->generateContractService->execute("KNNNNNNNNvsKN");
        self::assertCount(9, $contract->getPlaintiffSignatures());
        self::assertCount(2, $contract->getDefendantSignatures());
    }



}
