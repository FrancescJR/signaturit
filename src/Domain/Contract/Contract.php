<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Contract;

use Signaturit\Cesc\Domain\Signature\Signature;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;

class Contract
{
    public const SEPARATOR_VALUE = 'vs';
    public const CONTRACT_NUM_PARTIES = 2;
    public const PLAINTIFF_SIDE = 'plaintiff';
    public const DEFENDANT_SIDE = 'defendant';

    /**@var Signature[] */
    private $plaintiffSignatures;

    /**@var Signature[] */
    private $defendantSignatures;

    /**
     * Contract constructor.
     *
     * @param Signature[] $plaintiffSignatures
     * @param Signature[] $defendantSignatures
     */
    public function __construct(array $plaintiffSignatures, array $defendantSignatures)
    {
        $this->plaintiffSignatures = $plaintiffSignatures;
        $this->defendantSignatures = $defendantSignatures;
    }

    /**
     * @return Signature[]
     */
    public function getPlaintiffSignatures(): array
    {
        return $this->plaintiffSignatures;
    }

    /**
     * @return Signature[]
     */
    public function getDefendantSignatures(): array
    {
        return $this->defendantSignatures;
    }

    /**
     * @param Signature $signature
     */
    public function addPlaintiffSignature(Signature $signature): void
    {
        $this->plaintiffSignatures[] = $signature;
    }

    /**
     * @param Signature $signature
     */
    public function addDefendantSignature(Signature $signature): void
    {
        $this->defendantSignatures[] = $signature;
    }

    public function getPlaintiffScore(): int
    {
        return $this->calculateSignaturesScore($this->plaintiffSignatures);
    }

    public function getDefendantScore(): int
    {
        return $this->calculateSignaturesScore($this->defendantSignatures);
    }

    public function getEmptySignatureSide(): ?string
    {
        if ($this->checkForEmptySignature($this->plaintiffSignatures)) {
            return self::PLAINTIFF_SIDE;
        }
        if ($this->checkForEmptySignature($this->defendantSignatures)) {
            return self::DEFENDANT_SIDE;
        }
        return null;
    }

    /**
     * @param Signature[] $signaturesList
     *
     * @return int
     */
    private function calculateSignaturesScore(array $signaturesList): int
    {
        usort($signaturesList, 'self::sortSignatures');

        $value = 0;
        $keyHasSigned = false;

        // signatures list is sorted with kings first
        foreach ($signaturesList as $signature) {
            if ($signature->getRole()->value() == SignatureRole::ROLE_KING) {
                $keyHasSigned = true;
            }
            if ($keyHasSigned && $signature->getRole()->value() == SignatureRole::ROLE_VALIDATOR) {
                continue;
            }
            $value += $signature->getValue()->value();
        }

        return $value;
    }

    private static function sortSignatures(Signature $signatureA, Signature $signatureB): int
    {
        return $signatureA->getRole()->value() == SignatureRole::ROLE_KING ? -1 : 1;
    }

    /**
     * @param Signature[] $signaturesList
     *
     * @return bool
     */
    private function checkForEmptySignature(array $signaturesList): bool
    {
        foreach ($signaturesList as $signature) {
            if ($signature->getRole()->value() == SignatureRole::ROLE_EMPTY) {
                return true;
            }
        }
        return false;
    }

}
