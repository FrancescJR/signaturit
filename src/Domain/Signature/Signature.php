<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Signature;

use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureRole;
use Signaturit\Cesc\Domain\Signature\ValueObject\SignatureValue;

class Signature
{
    /**
     * @var SignatureRole
     */
    private $role;

    /**
     * @var SignatureValue
     */
    private $value;

    public function __construct(
        SignatureRole $role,
        SignatureValue $value
    ) {
        $this->role  = $role;
        $this->value = $value;
    }

    public function getValue(): SignatureValue
    {
        return $this->value;
    }

    public function getRole(): SignatureRole
    {
        return $this->role;
    }

}
