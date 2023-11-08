<?php

declare(strict_types=1);

namespace Microservice\Domain\ValueObject;

use Microservice\Domain\Shared\ValueObject\StringValueObject;
use Symfony\Component\Console\Exception\InvalidArgumentException;

final class Email extends StringValueObject
{
    private string $email;

    public function __construct(string $email)
    {
        $this->ensureIsValidEmail($email);
        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;
    }

    private function ensureIsValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('The email "%s" is not a valid email address.', $email));
        }

        // Additional checks if needed
        if (substr_count($email, '@') !== 1) {
            throw new InvalidArgumentException('Email must contain exactly one @ symbol.');
        }

        if (substr_count($email, '.') === 0) {
            throw new InvalidArgumentException('Email must contain at least one dot character.');
        }
    }
}
