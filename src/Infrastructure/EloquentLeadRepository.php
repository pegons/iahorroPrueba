<?php

declare(strict_types=1);

namespace Microservice\Infrastructure;


use App\Models\Lead;
use Microservice\Domain\LeadDomain;
use Microservice\Domain\Repositories\LeadRepositoryInterface;


class EloquentLeadRepository implements LeadRepositoryInterface
{
    /**
     * Create Lead.
     *
     * @param LeadDomain $lead
     *
     * @return LeadDomain
     */
    public function create(LeadDomain $lead): LeadDomain
    {
        $result = Lead::create([
            'id' => $lead->uuid(),
            'name' => $lead->name()->value(),
            'email' => $lead->email()->value(),
            'phone' => $lead->phone() ? $lead->phone()->value() : null,
            'created_at' => $lead->getCreatedAt(),
            'updated_at' => (string)now()
        ]);

        return self::toDomain($result);
    }


    /**
     * Find Lead by ID.
     *
     * @param string $leadId
     *
     * @return LeadDomain
     */
    public function find(string $leadId): ?LeadDomain
    {
        $result = Lead::findOrFail($leadId);
        return self::toDomain($result);
    }

    /**
     * Update Lead.
     *
     * @param LeadDomain $lead
     *
     * @return LeadDomain
     */
    public function update(LeadDomain $lead): LeadDomain
    {
        $leadModel = Lead::findOrFail($lead->uuid());

        $leadModel->update([
            'name' => $lead->name()->value(),
            'email' => $lead->email()->value(),
            'phone' => $lead->phone() ? $lead->phone()->value() : null,
            'updated_at' => (string)now()
        ]);

        return self::toDomain($leadModel);
    }

    public function delete(string $id): void
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
    }

    /**
     * Mapper eloquent model to domain model.
     *
     * @param Lead $model
     *
     * @return LeadDomain
     */
    public static function toDomain(Lead $model): LeadDomain
    {
        return new LeadDomain([
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
            'phone' => $model->phone,
            'createdAt' => $model->created_at->toDateTimeString(),
        ]);
    }
}
