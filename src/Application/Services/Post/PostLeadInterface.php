<?php

namespace Microservice\Application\Services\Post;

use App\Dtos\PostLeadDto;

interface PostLeadInterface
{
    public function __invoke(PostLeadDto $dto): void;
}
