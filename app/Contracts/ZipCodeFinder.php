<?php

namespace App\Contracts;

use App\DTOs\ZipCodeFinderResponse;

interface ZipCodeFinder
{
    public function execute(string $zipCode): ?ZipCodeFinderResponse;
}
