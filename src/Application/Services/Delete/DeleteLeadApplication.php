<?php

declare(strict_types=1);

namespace Microservice\Application\Services\Delete;

use Throwable;
use Microservice\Domain\Exceptions\LeadDeleteException;
use Microservice\Application\Services\Delete\DeleteLeadInterface;
use Microservice\Domain\Repositories\LeadRepositoryInterface;

class DeleteLeadApplication implements DeleteLeadInterface
{
    private LeadRepositoryInterface $leadRepository;

    public function __construct(LeadRepositoryInterface $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    /**
     * Delete an existing Lead.
     *
     * @param string $id
     * @return void
     */
    public function __invoke(string $id): void
    {
        try {
            $lead = $this->leadRepository->find($id);
            if (!$lead) {
                throw new LeadDeleteException("Lead not found with ID: {$id}");
            }
            $this->leadRepository->delete($id);
        } catch (Throwable $e) {
            throw new LeadDeleteException("Unable to delete lead: " . $e->getMessage());
        }
    }
}
