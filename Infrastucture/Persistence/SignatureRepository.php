<?php
declare(strict_types=1);

class SignatureRepository implements SignatureRepositoryInterface
{
    private const ROLES_IN_DATABASE = [
        "K" => 5,
        "N" => 2,
        "V" => 1
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

        // TODO remember here: should I make a new value object or reuse the one I got?
        return new Signature(
            $role,
            new SignatureValue(self::ROLES_IN_DATABASE[$role->value()])
        );
    }
}
