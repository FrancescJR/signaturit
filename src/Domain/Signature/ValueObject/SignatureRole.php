<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Domain\Signature\ValueObject;

use Signaturit\Cesc\Domain\Signature\Exception\InvalidSignatureRoleValueException;

class SignatureRole
{
    public const ROLE_KING = 'K';
    public const ROLE_NOTARY = 'N';
    public const ROLE_VALIDATOR = 'V';
    public const ROLE_EMPTY = '#';

    public const ROLES =[
        self::ROLE_KING,
        self::ROLE_NOTARY,
        self::ROLE_VALIDATOR,
        self::ROLE_EMPTY
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     * @throws InvalidSignatureRoleValueException
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @throws InvalidSignatureRoleValueException
     */
    private function setValue(string $value): void
    {
        if ( ! in_array($value, self::ROLES)) {
            throw new InvalidSignatureRoleValueException("The signature {$value} is invalid");
        }

        $this->value = $value;
    }

}
