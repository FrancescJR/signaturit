<?php
declare(strict_types=1);

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

        foreach($signaturesList as $signature) {
            $value += $signature->getValue()->value();
        }

        return $value;
    }

}
