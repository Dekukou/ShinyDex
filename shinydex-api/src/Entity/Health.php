<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;

#[ApiResource(
    formats: ['json'],
    operations: [
        new Get(
            uriTemplate: '/health'
        )
    ]
)]
class Health
{
    public function getStatus(): string
    {
        return 'ok';
    }
}
