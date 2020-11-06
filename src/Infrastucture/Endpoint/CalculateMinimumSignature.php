<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Infrastructure\Endpoint;


use Signaturit\Cesc\Application\CalculateMinimumSignatureService;
use Signaturit\Cesc\Application\Exception\NoSideHasEmptySignatureException;
use Signaturit\Cesc\Domain\Contract\Exception\InvalidContractFormatException;
use Signaturit\Cesc\Domain\Contract\Exception\TooManyEmptySignaturesException;
use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;

class CalculateMinimumSignature
{
    private $calculateService;

    public function __construct(CalculateMinimumSignatureService $calculateService)
    {
        $this->calculateService = $calculateService;
    }

    /**
     * @param string $contractString
     *
     * @return string
     * @throws NoSideHasEmptySignatureException
     * @throws InvalidContractFormatException
     * @throws TooManyEmptySignaturesException
     * @throws InvalidSignatureRoleValueException
     */
    public function calculateMinimumSignature(string $contractString): string
    {
        try {
            return $this->calculateService->execute($contractString);
        } catch (SignatureNotFoundException $e) {
            return "No signature has the enough power to make this side win.";
        }
    }

}
