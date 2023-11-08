<?php

declare(strict_types=1);

namespace Microservice\Application\Services\Put;

use Throwable;
use App\Dtos\PutLeadDto;
use Microservice\Domain\ValueObject\Name;
use Microservice\Domain\ValueObject\Email;
use Microservice\Domain\ValueObject\Phone;
use Microservice\Domain\Exceptions\LeadUpdateException;
use Microservice\Application\Services\Put\PutLeadInterface;
use Microservice\Domain\Repositories\LeadRepositoryInterface;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;

class PutLeadApplication implements PutLeadInterface
{
    private LeadRepositoryInterface $leadRepository;
    private LeadScoringRepositoryInterface $scoringRepository;

    public function __construct(LeadRepositoryInterface $leadRepository, LeadScoringRepositoryInterface $scoringRepository)
    {
        $this->leadRepository = $leadRepository;
        $this->scoringRepository = $scoringRepository;
    }

    /**
     * Update an existing Lead.
     *
     * @param PutLeadDto $dto
     * @return void
     */
    public function __invoke(PutLeadDto $dto): void
    {
        try {
            $lead = $this->leadRepository->find($dto->uuid);

            if (isset($dto->name)) {
                $lead->setName(new Name($dto->name));
            }
            if (isset($dto->email)) {
                $lead->setEmail(new Email($dto->email));
            }
            if (isset($dto->phone)) {
                $lead->setPhone(new Phone($dto->phone));
            }

            $lead = $lead->calculateMyScoring($this->scoringRepository);

            $this->leadRepository->update($lead);
        } catch (Throwable $e) {
            throw new LeadUpdateException("Unable to update lead: " . $e->getMessage());
        }
    }
}
