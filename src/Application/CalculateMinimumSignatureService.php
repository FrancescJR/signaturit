<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Application;


use Signaturit\Cesc\Application\Exception\TooManyEmptySignaturesException;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;

class CalculateMinimumSignatureService
{
    public const MISSING_SIGNATURE = '#';

    private $generateContractService;

    public function __construct(GenerateContractService $generateContractService)
    {
        $this->generateContractService = $generateContractService;
    }

    public function execute(string $contractSignatures)
    {
        if (substr_count($contractSignatures, self::MISSING_SIGNATURE) != 1) {
            throw new TooManyEmptySignaturesException(
                "Only one signature can be empty either in the plaintiff or the defendant side."
            );
        }



    }

}
