<?php

declare(strict_types=1);

namespace Microservice\Application\Services\Get;

use App\Dtos\GetLeadDto;
use Microservice\Application\Mappers\LeadMapper;
use Microservice\Domain\Repositories\LeadRepositoryInterface;

class GetLeadApplication implements GetLeadInterface
{
    private LeadRepositoryInterface $rideServiceRepository;
    private LeadMapper $leadMapper;

    public function __construct(
        LeadRepositoryInterface $rideServiceRepository,
        LeadMapper $leadMapper
    ) {
        $this->rideServiceRepository = $rideServiceRepository;
        $this->leadMapper = $leadMapper;
    }

    /**
     * Get a lead from a id.
     *
     * @param string  $leadId
     *
     * @return GetLeadDto
     */
    public function __invoke(string $leadId): GetLeadDto
    {
        $rideServiceDomainModel = $this->rideServiceRepository->find($leadId);

        return $this->leadMapper->fromDomainToDto($rideServiceDomainModel);
    }
}
