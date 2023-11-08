<?php

namespace Microservice\Application\Services\Delete;


interface DeleteLeadInterface
{
    public function __invoke(string $id): void;
}
