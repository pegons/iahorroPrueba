<?php

declare(strict_types=1);

namespace Microservice\Application\Mappers;

use App\Dtos\GetLeadDto;
use Microservice\Domain\LeadDomain;

class LeadMapper
{
    /**
     * Create a get dto from domain.
     *
     * @param LeadDomain $leadDomain
     *
     * @return GetLeadDto
     */
    public function fromDomainToDto(LeadDomain $leadDomain): GetLeadDto
    {
        return new GetLeadDto([
            'id' => $leadDomain->uuid(),
            'name' => $leadDomain->name()->value(),
            'email' => $leadDomain->email()->value(),
            'score' => $leadDomain->score(),
            'phone' => $leadDomain->phone() ? $leadDomain->phone()->value() : null,
            'createdAt' => $leadDomain->getCreatedAt(),
            'updatedAt' => (string)now()
        ]);
    }

}
