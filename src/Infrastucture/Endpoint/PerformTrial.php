<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Infrastructure\Endpoint;


use Signaturit\Cesc\Application\PerformTrialService;
use Signaturit\Cesc\Domain\Contract\Exception\InvalidContractFormatException;
use Signaturit\Cesc\Domain\Contract\Exception\TooManyEmptySignaturesException;
use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;

// that would be a controller for example where to make http requests for example.
class PerformTrial
{
    private $performTrialService;

    public function __construct(PerformTrialService $performTrialService)
    {
        $this->performTrialService = $performTrialService;
    }

    /**
     * @param string $contractString
     *
     * @return string
     * @throws InvalidContractFormatException
     * @throws TooManyEmptySignaturesException
     * @throws InvalidSignatureRoleValueException
     * @throws SignatureNotFoundException
     */
    public function performTrial(string $contractString): string
    {
        return $this->performTrialService->execute($contractString);
    }

}
