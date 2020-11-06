<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;


use Signaturit\Cesc\Application\Exception\EmptySignatureSideIsAlreadyWinningException;
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
     * @throws EmptySignatureSideIsAlreadyWinningException
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

        if ($scoreToReach < 1) {
            throw new EmptySignatureSideIsAlreadyWinningException(
                "Empty signature side has already the same score or bigger than the other side."
            );
        }


        $signature = $this->signatureRepository->getSmallerSignatureBiggerThan($scoreToReach);

        // Final check, if the signature found is a king one, it will invalidate validators signatures,
        // so even with the king signature might not be enough.
        if ($contract->getEmptySignatureSide() == Contract::PLAINTIFF_SIDE) {
            $contract->addPlaintiffSignature($signature);
            if ($contract->getPlaintiffScore() < $contract->getDefendantScore()) {
                throw new SignatureNotFoundException("Not even King makes it.");
            }
        } else {
            $contract->addDefendantSignature($signature);
            if ($contract->getDefendantScore() < $contract->getPlaintiffScore()) {
                throw new SignatureNotFoundException("Not even King makes it.");
            }
        }


        return $signature->getRole()->value();

    }

}
