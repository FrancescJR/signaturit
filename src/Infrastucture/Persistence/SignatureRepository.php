<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Infrastructure\Persistence;

use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;
use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;
use Signaturit\Cesc\Domain\Signature\Signature;
use Signaturit\Cesc\Domain\Signature\SignatureRepositoryInterface;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureValue;

class SignatureRepository implements SignatureRepositoryInterface
{
    private const ROLES_IN_DATABASE = [
        "K" => 5,
        "N" => 2,
        "V" => 1,
        "#" => 0
    ];

    /**
     * @param SignatureRole $role
     *
     * @return Signature
     * @throws SignatureNotFoundException
     */
    public function findByRole(SignatureRole $role): Signature
    {
        if ( ! array_key_exists($role->value(), self::ROLES_IN_DATABASE)) {
            throw new SignatureNotFoundException("No signature found with this role");
        }

        return new Signature(
            $role,
            new SignatureValue(self::ROLES_IN_DATABASE[$role->value()])
        );
    }

    /**
     * @param int $value
     *
     * @return Signature
     * @throws SignatureNotFoundException
     * @throws InvalidSignatureRoleValueException
     */
    public function getSmallerSignatureBiggerThan(int $value): Signature
    {
        $smallerSignature = null;
        foreach (self::ROLES_IN_DATABASE as $role => $signatureValue) {
            if ($signatureValue >= $value) {
                $signature = new Signature(
                    new SignatureRole($role),
                    new SignatureValue($signatureValue)
                );
                if ( ! $smallerSignature or $signature->getValue()->value() < $smallerSignature->getValue()->value()) {
                    $smallerSignature = $signature;
                }
            }
        }
        if ($smallerSignature) {
            return $smallerSignature;
        }
        throw new SignatureNotFoundException("No signature bigger than the value {$value} exists");
    }
}
