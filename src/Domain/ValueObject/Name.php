<?php

declare(strict_types=1);

namespace Microservice\Domain\ValueObject;

use Microservice\Domain\Exceptions\NameCreateException;
use Microservice\Domain\Shared\ValueObject\StringValueObject;

final class Name extends StringValueObject
{
    private string $name;

    public function __construct(string $data)
    {
        if ($data != "" && $data != null){
            $this->name = $data;
        }else
        {
            throw new NameCreateException("Error trying to create a Name");
        }
    }

    public function value(): string
    {
        return $this->name;
    }
}
