<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;


use Signaturit\Cesc\Application\Exception\NoSideHasEmptySignatureException;
use Signaturit\Cesc\Domain\Contract\Contract;
use Signaturit\Cesc\Domain\Contract\Exception\InvalidContractFormatException;
use Signaturit\Cesc\Domain\Contract\Exception\TooManyEmptySignaturesException;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;
use Signaturit\Cesc\Domain\Signature\SignatureRepositoryInterface;

class CalculateMinimumSignatureService
{

    private $generateContractService;

    private $signatureRepository;

    public function __construct(
        GenerateContractService $generateContractService,
        SignatureRepositoryInterface $signatureRepository
    ) {
        $this->generateContractService = $generateContractService;
        $this->signatureRepository     = $signatureRepository;
    }

    /**
     * @param string $contractSignatures
     *
     * @return string
     * @throws NoSideHasEmptySignatureException
     * @throws SignatureNotFoundException
     * @throws InvalidContractFormatException
     * @throws TooManyEmptySignaturesException
     * @throws InvalidSignatureRoleValueException
     */
    public function execute(string $contractSignatures): string
    {
        $contract = $this->generateContractService->execute($contractSignatures);

        if ( ! $contract->getEmptySignatureSide()) {
            throw new NoSideHasEmptySignatureException(
                "The service cant determine anything without an empty signature in the contract."
            );
        }

        $scoreToReach = $contract->getEmptySignatureSide() == Contract::PLAINTIFF_SIDE ?
            $contract->getDefendantScore() - $contract->getPlaintiffScore() :
            $contract->getPlaintiffScore() - $contract->getDefendantScore();


        $signature = $this->signatureRepository->getSmallerSignatureBiggerThan($scoreToReach);


        return $signature->getRole()->value();

    }

}
