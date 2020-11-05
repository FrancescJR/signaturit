<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Contract;

use Signaturit\Cesc\Domain\Signature\Signature;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;

class Contract
{
    public const SEPARATOR_VALUE = 'vs';
    public const CONTRACT_NUM_PARTIES = 2;

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
        usort($plaintiffSignatures, 'self::sortSignatures');
        usort($defendantSignatures, 'self::sortSignatures');
        $this->plaintiffSignatures = $plaintiffSignatures;
        $this->defendantSignatures = $defendantSignatures;
    }

    private static function sortSignatures(Signature $signatureA, Signature $signatureB): int
    {
        return $signatureA->getRole()->value() == SignatureRole::ROLE_KING ? -1 : 1;
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

}
