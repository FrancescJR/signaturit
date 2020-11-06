<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;

use Signaturit\Cesc\Domain\Contract\Exception\InvalidContractFormatException;
use Signaturit\Cesc\Domain\Contract\Exception\TooManyEmptySignaturesException;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;

class PerformTrialService
{
    public const PLAINTIFF_WINS = 'plaintiff wins';
    public const DEFENDANT_WINS = 'defendant wins';
    public const EQUAL_CLAIMS = 'both plaintiff and defendant have equal claims';

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
     * @throws TooManyEmptySignaturesException
     */
    public function execute(string $contractWeirdFormat): string
    {
        // try to create the contract
        $contract = $this->generateContractService->execute($contractWeirdFormat);

        if ($contract->getPlaintiffScore() == $contract->getDefendantScore()) {
            return self::EQUAL_CLAIMS;
        }

        return $contract->getPlaintiffScore() > $contract->getDefendantScore() ?
            self::PLAINTIFF_WINS : self::DEFENDANT_WINS;
    }

}
