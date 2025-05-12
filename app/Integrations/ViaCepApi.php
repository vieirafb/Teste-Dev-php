<?php

namespace App\Integrations;

use App\Contracts\ZipCodeFinder;
use App\DTOs\ZipCodeFinderResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViaCepApi implements ZipCodeFinder
{
    private string $baseUrl = 'https://viacep.com.br/ws/{zipCode}/json';

    public function execute(string $zipCode): ?ZipCodeFinderResponse
    {
        $url = str_replace('{zipCode}', $zipCode, $this->baseUrl);
        $response = Http::get($url);

        if (!$response->successful()) {
            Log::error("Unexpected error! [{$response->status()}]: {$response->json()}");
            throw new \RuntimeException("Unexpected error checking zip code");
        }

        $data = $response->json();

        if (isset($data['erro'])) return null;

        return ZipCodeFinderResponse::create([
            'zipCode' => $zipCode,
            'street' => $data['logradouro'] ?: null,
            'neighborhood' => $data['bairro'] ?: null,
            'city' => $data['localidade'],
            'uf' => $data['uf'],
            'state' => $data['estado'],
            'ibgeCode' => $data['ibge'],
        ]);
    }
}
