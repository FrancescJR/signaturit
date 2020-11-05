<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;

use Signaturit\Cesc\Domain\Contract\Exception\InvalidContractFormatException;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;
use Signaturit\Cesc\Domain\Signature\Signature;

class performTrialService
{
    public const PLAINTIFF_WINS = 'plaintiff wins';
    public const DEFENDANT_WINS = 'defendant wins';

    private $generateContractService;

    public function __construct(GenerateContractService $generateContractService)
    {
        $this->generateContractService = $generateContractService;
    }

    /**
     * @param string $contractWeirdFormat
     *
     * @return string
     * @throws InvalidContractFormatException
     * @throws InvalidSignatureRoleValueException
     * @throws SignatureNotFoundException
     */
    public function execute(string $contractWeirdFormat): string
    {
        // try to create the contract
        $contract = $this->generateContractService->execute($contractWeirdFormat);

        $plaintiffScore = $this->calculateSignaturesListScore($contract->getPlaintiffSignatures());
        $defendantScore = $this->calculateSignaturesListScore($contract->getDefendantSignatures());

        return $plaintiffScore >= $defendantScore ? self::PLAINTIFF_WINS : self::DEFENDANT_WINS;
    }

    /**
     * @param Signature[] $signaturesList
     *
     * @return int
     */
    private function calculateSignaturesListScore(array $signaturesList): int
    {
        $value = 0;

        foreach ($signaturesList as $signature) {
            $value += $signature->getValue()->value();
        }

        return $value;
    }

}