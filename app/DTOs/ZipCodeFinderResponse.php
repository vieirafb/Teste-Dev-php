<?php

namespace App\DTOs;

class ZipCodeFinderResponse
{
    public function __construct(
        readonly string  $zipCode,
        readonly ?string $street = null,
        readonly ?string $neighborhood = null,
        readonly string  $city,
        readonly string  $uf,
        readonly string  $state,
        readonly string  $ibgeCode,
    ) {}

    static public function create(array $data): self
    {
        return new self(
            $data['zipCode'],
            $data['street'] ?? null,
            $data['neighborhood'] ?? null,
            $data['city'],
            $data['uf'],
            $data['state'],
            $data['ibgeCode'],
        );
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
