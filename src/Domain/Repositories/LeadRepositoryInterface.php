<?php

declare(strict_types=1);

namespace Microservice\Domain\Repositories;

use App\Models\Lead;
use Microservice\Domain\LeadDomain;

interface LeadRepositoryInterface
{
    public function create(LeadDomain $rideService): LeadDomain;

    public function find(string $rideServiceId): ?LeadDomain;

    public static function toDomain(Lead $model): LeadDomain;

    public function delete(string $id): void;

    public function update(LeadDomain $rideService): LeadDomain;
}
