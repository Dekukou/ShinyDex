<?php

namespace App\Service\PokeApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeApiClient
{
    public function __construct(private HttpClientInterface $client) {}

    public function get(string $endpoint): array
    {
        return $this->client
            ->request('GET', 'https://pokeapi.co/api/v2/' . ltrim($endpoint, '/'))
            ->toArray();
    }

    public function getByUrl(string $url): array
    {
        $response = $this->client->request('GET', $url);

        return $response->toArray();
    }
}
