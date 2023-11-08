<?php

declare(strict_types=1);

namespace Microservice\Domain\ValueObject;

use Microservice\Domain\Shared\ValueObject\StringValueObject;
use Symfony\Component\Console\Exception\InvalidArgumentException;

final class Phone
{
    private ?string $phone;

    public function __construct(?string $phone)
    {
        if ($phone != null){
            $this->ensureIsValidPhone($phone);
            $this->phone = $phone;
        }else{
            $this->phone = null;
        }

    }

    public function value(): ?string
    {
        return $this->phone;
    }

    private function ensureIsValidPhone(string $phone): void
    {
        if (!preg_match('/^\+?[1-9]\d{1,14}$/', $phone)) {
            throw new InvalidArgumentException(sprintf('The phone number "%s" is not a valid international phone number.', $phone));
        }
    }
}
