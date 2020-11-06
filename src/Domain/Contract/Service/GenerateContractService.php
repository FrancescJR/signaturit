<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Contract\Service;

use Signaturit\Cesc\Domain\Contract\Exception\TooManyEmptySignaturesException;
use Signaturit\Cesc\Domain\Contract\Exception\InvalidContractFormatException;
use Signaturit\Cesc\Domain\Contract\Contract;
use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;
use Signaturit\Cesc\Domain\Signature\SignatureRepositoryInterface;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;

class GenerateContractService
{
    public const MISSING_SIGNATURE = '#';

    /**
     * @var SignatureRepositoryInterface
     */
    private $signatureRepository;

    public function __construct(SignatureRepositoryInterface $signatureRepository)
    {
        $this->signatureRepository = $signatureRepository;
    }


    /**
     * @param string $contractWeirdFormat
     *
     * @throws InvalidContractFormatException
     * @throws InvalidSignatureRoleValueException
     * @throws SignatureNotFoundException
     * @throws TooManyEmptySignaturesException
     *
     * @return Contract
     */
    public function execute(string $contractWeirdFormat): Contract
    {
        $this->checkContractFormat($contractWeirdFormat);

        $partiesLists = explode(Contract::SEPARATOR_VALUE, $contractWeirdFormat);

        if (count($partiesLists) != Contract::CONTRACT_NUM_PARTIES ) {
            throw new InvalidContractFormatException("Contract should be in KN vs NNV format");
        }

        // order of checks is important, or we could have something like KNv#sKN
        if (substr_count($contractWeirdFormat, self::MISSING_SIGNATURE) > 1) {
            throw new TooManyEmptySignaturesException(
                "Only one signature can be empty either in the plaintiff or the defendant side."
            );
        }

        $plaintiffSignatures = $this->generateSignatures($partiesLists[0]);
        $defendantSignatures = $this->generateSignatures($partiesLists[1]);

        return new Contract(
            $plaintiffSignatures,
            $defendantSignatures
        );
    }


    /**
     * @param string $signaturesList
     *
     * @return array
     * @throws InvalidSignatureRoleValueException
     * @throws SignatureNotFoundException
     */
    private function generateSignatures(string $signaturesList): array
    {
        $partySignatures = [];

        $roles = str_split($signaturesList);

        foreach ($roles as $role) {
            $partySignatures[] = $this->signatureRepository->findByRole(new SignatureRole($role));
        }

        return $partySignatures;
    }

    /**
     * @param string $signaturesList
     *
     * @throws InvalidContractFormatException
     */
    private function checkContractFormat(string $signaturesList)
    {
        if ( ! strpos( $signaturesList, Contract::SEPARATOR_VALUE)) {
            throw new InvalidContractFormatException("Contract should be in KN vs NNV format");
        }
    }

}
