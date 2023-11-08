<?php

declare(strict_types=1);

namespace Microservice\Domain;

use Throwable;
use Illuminate\Support\Str;
use Microservice\Domain\ValueObject\Name;
use Microservice\Domain\ValueObject\Email;
use Microservice\Domain\ValueObject\Phone;
use Microservice\Domain\Shared\ValueObject\Uuid;
use Microservice\Domain\Exceptions\DomainCreateException;
use Microservice\Domain\Repositories\ClientRepositoryInterface;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;

class LeadDomain
{
    private Uuid $id;
    private Name $name;
    private Email $email;
    private Phone $phone;
    private ?int $score;
    private ?String $createdAt;

    public function __construct(array $param)
    {
        try {
            $this->id = !empty($param['id']) ? new Uuid($param['id']) : new Uuid((string)Str::uuid());
            $this->name = new Name($param['name']);
            $this->email = new Email($param['email']);
            $this->phone = new Phone($param['phone']);
            $this->score = null;
            $this->createdAt = !empty($param['createdAt']) ? $param['createdAt'] : (string)now();
        } catch (Throwable $e) {
            throw new DomainCreateException("Error trying to create a Lead by" . $e->getMessage());
        }
    }

    public function uuid(): string
    {
        return $this->id->value();
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function phone(): ?Phone
    {
        return $this->phone;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function score(): ?int
    {
        return $this->score;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function setPhone(Phone $phone): void
    {
        $this->phone = $phone;
    }

    /*
        Aquí he decidido darle algo de funcionalidad a lead ya que si no se queda un poco como dominio anemico,
        se podrían añadir mas cosas, pero esto es un ejemplo de como usar los dominios, para que no queden anemicos
    */
    public function calculateMyScoring(LeadScoringRepositoryInterface $scouringRepository): LeadDomain
    {
        $this->score = $scouringRepository->calculate($this);
        return $this;
    }

    public function generateClient(ClientRepositoryInterface $clientRepository): ClientDomain
    {
        return $clientRepository->create($this);
    }
}
