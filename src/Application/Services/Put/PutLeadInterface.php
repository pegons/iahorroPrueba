<?php

namespace Microservice\Application\Services\Put;

use App\Dtos\PutLeadDto;

interface PutLeadInterface
{
    public function __invoke(PutLeadDto $dto): void;
}
