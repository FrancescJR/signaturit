<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Signature;

use Signaturit\Cesc\Domain\Signature\Exception\SignatureNotFoundException;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;

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
