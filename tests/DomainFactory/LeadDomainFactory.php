<?php

namespace Tests\DomainFactory;

use Microservice\Domain\LeadDomain;

use Microservice\Domain\Shared\ValueObject\Uuid;

class LeadDomainFactory
{
    public static function create(?array $params = []): LeadDomain
    {
        $faker = \Faker\Factory::create();

        $defaultParams = [
            'id' => Uuid::random()->value(),
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => "666777888",
            'createdAt' => "01-01-2024 10:00:00",
            'updatedAt' => "01-01-2024 10:00:01"
        ];

        return new LeadDomain(array_merge($defaultParams, $params));
    }
}
