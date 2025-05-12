<?php

namespace App\Contracts;

interface CustomerRepositoryInterface
{
    public function create(array $data): array;

    public function edit(int $id, array $data): array;

    public function get(int $id): array;

    public function delete(int $id): void;
}
