<?php

declare(strict_types=1);

namespace Microservice\Domain\Repositories;

use Microservice\Domain\ClientDomain;
use Microservice\Domain\LeadDomain;

interface ClientRepositoryInterface
{
    public function create(LeadDomain $lead): ClientDomain;
}
