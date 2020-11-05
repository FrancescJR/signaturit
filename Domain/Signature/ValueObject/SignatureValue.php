<?php
declare(strict_types=1);

class SignatureValue
{
    private $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        // maybe check it is a positive integer, but well, maybe some signatures might count against you :)
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

}
