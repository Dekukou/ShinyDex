<?php

namespace App\Service\PokeApi;

use App\Service\PokeApi\PokeApiClient;

class PokeApiTranslationHelper
{
    public function __construct(
        private readonly PokeApiClient $client
    ) {}

    public function getName(array $names, string $lang = 'fr', string $fallback = 'en'): string
    {
        $fallbackName = null;

        foreach ($names as $entry) {
            if ($entry['language']['name'] === $fallback) {
                $fallbackName = $entry['name'];
            }

            if ($entry['language']['name'] === $lang) {
                return $entry['name'];
            }
        }

        return $fallbackName ?? $names[0]['name'];
    }

    public function getPokemonName(array $names, string $language): ?string
    {
        foreach ($names as $entry) {
            if (
                isset($entry['language']['name'], $entry['name']) &&
                $entry['language']['name'] === $language
            ) {
                return $entry['name'];
            }
        }

        return null;
    }

    public function getFrenchGenus(array $genera): ?string
    {
        foreach ($genera as $entry) {
            if (
                isset($entry['language']['name'], $entry['genus']) &&
                $entry['language']['name'] === 'fr'
            ) {
                return $entry['genus'];
            }
        }

        return null;
    }

    public function getFlavorText(array $entries, string $lang = 'fr'): ?string
    {
        foreach ($entries as $entry) {
            if ($entry['language']['name'] === $lang) {
                return preg_replace('/\s+/', ' ', $entry['flavor_text']);
            }
        }

        return null;
    }

    public function getFrenchName(string $apiName, string $endpoint): ?string
    {
        $data = $this->client->get($endpoint . '/' . $apiName);

        foreach ($data['names'] ?? [] as $name) {
            if ($name['language']['name'] === 'fr') {
                return $name['name'];
            }
        }

        return null;
    }
}
