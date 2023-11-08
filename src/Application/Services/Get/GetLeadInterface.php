<?php

namespace Microservice\Application\Services\Get;

use App\Dtos\GetLeadDto;

interface GetLeadInterface
{
    public function __invoke(string $leadId): GetLeadDto;
}
