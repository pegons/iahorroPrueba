<?php

declare(strict_types=1);
namespace Microservice\Infrastructure;

use App\Models\Client;
use Illuminate\Support\Str;
use Microservice\Domain\LeadDomain;
use Microservice\Domain\ClientDomain;
use Microservice\Domain\Shared\ValueObject\Uuid;
use Microservice\Domain\Repositories\ClientRepositoryInterface;

class EloquentClientRepository implements ClientRepositoryInterface
{
    /**
     * Create Client with Lead ID.
     *
     * @param ClientDomain $client
     *
     * @return ClientDomain
     */
    public function create(LeadDomain $lead): ClientDomain
    {
        $result = Client::create([
            'id' => new Uuid((string)Str::uuid()),
            'lead_id' => $lead->uuid(),
        ]);

        return self::toDomain($result);
    }

    /**
     * Mapper eloquent model to domain model.
     *
     * @param Client $model
     *
     * @return ClientDomain
     */
    public static function toDomain(Client $model): ClientDomain
    {
        return new ClientDomain([
            'id' => $model->id,
            'leadId' => $model->lead_id,
        ]);
    }
}
