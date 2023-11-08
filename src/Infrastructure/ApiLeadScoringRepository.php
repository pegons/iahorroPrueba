<?php

declare(strict_types=1);

namespace Microservice\Infrastructure;

use Microservice\Domain\LeadDomain;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;


class ApiLeadScoringRepository implements LeadScoringRepositoryInterface
{
    /**
     * Create Lead.
     *
     * @param LeadDomain $lead
     *
     * @return LeadDomain
     */
    public function calculate(LeadDomain $lead): int
    {
        /*
            Si esto fuera una api externa, simplemente con un cliente por ejemplo guzzle
            pediriamos los datos y mandabamos lo que necesitamos y obtendriamos la puntuacion
        */
        return rand(1, 100);
    }


}
