<?php
declare(strict_types=1);

class Signature
{
    private $role;

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

}
