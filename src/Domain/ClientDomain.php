<?php

declare(strict_types=1);

namespace Microservice\Domain;

use Throwable;
use Illuminate\Support\Str;
use Microservice\Domain\Shared\ValueObject\Uuid;
use Microservice\Domain\Exceptions\DomainCreateException;

class ClientDomain
{
    private Uuid $id;
    private Uuid $leadId;
    private ?String $createdAt;

    public function __construct(array $params)
    {
        try {
            $this->id = !empty($params['id']) ? new Uuid($params['id']) : new Uuid((string)Str::uuid());
            $this->leadId = new Uuid($params['leadId']);
            $this->createdAt = !empty($params['createdAt']) ? $params['createdAt'] : (string)now();
        } catch (Throwable $e) {
            throw new DomainCreateException("Error trying to create a Client by " . $e->getMessage());
        }
    }

    public function uuid(): string
    {
        return $this->id->value();
    }

    public function leadId(): Uuid
    {
        return $this->leadId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

}

