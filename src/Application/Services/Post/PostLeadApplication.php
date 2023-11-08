<?php

declare(strict_types=1);

namespace Microservice\Application\Services\Post;

use Throwable;
use App\Dtos\GetLeadDto;
use App\Dtos\PostLeadDto;
use Microservice\Domain\LeadDomain;
use Microservice\Domain\Exceptions\NewLeadCreateException;
use Microservice\Application\Services\Post\PostLeadInterface;
use Microservice\Domain\Repositories\LeadRepositoryInterface;
use Microservice\Domain\Repositories\ClientRepositoryInterface;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;

class PostLeadApplication implements PostLeadInterface
{
    private LeadRepositoryInterface $leadRepository;
    private LeadScoringRepositoryInterface $scoringRepository;
    private ClientRepositoryInterface $clientRepository;

    public function __construct(
        LeadRepositoryInterface $leadRepository,
        ClientRepositoryInterface $clientRepository,
        LeadScoringRepositoryInterface $scoringRepository
    ) {
        $this->leadRepository = $leadRepository;
        $this->scoringRepository = $scoringRepository;
        $this->clientRepository = $clientRepository;
    }

    /**
     * Create a new Lead.
     *
     * @param PostLeadDto  $dto
     *
     * @return void
     */
    public function __invoke(PostLeadDto $dto): void
    {
        try{
            $lead = new LeadDomain($dto->toArray());
            $lead = $lead->calculateMyScoring($this->scoringRepository);
            $newLead = $this->leadRepository->create($lead);
            /** Aquí he considerado que si un client depende de Lead, que sea el propio lead el que tenga la potesta para crearlo */
            $newLead->generateClient($this->clientRepository);
        }catch(Throwable $e){
            throw new NewLeadCreateException("Impossible create a lead by" . $e->getMessage());
        }
    }
}
