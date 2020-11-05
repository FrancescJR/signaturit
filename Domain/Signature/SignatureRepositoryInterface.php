<?php
declare(strict_types=1);

interface SignatureRepositoryInterface
{

    /**
     * @param SignatureRole $role
     *
     * @return Signature
     * @throws SignatureNotFoundException
     */
    public function findByRole(SignatureRole $role): Signature;

}
