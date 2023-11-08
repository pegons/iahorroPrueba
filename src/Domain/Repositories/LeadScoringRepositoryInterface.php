<?php

declare(strict_types=1);

namespace Microservice\Domain\Repositories;

use Microservice\Domain\LeadDomain;

interface LeadScoringRepositoryInterface
{
    public function calculate(LeadDomain $lead): int;
}
