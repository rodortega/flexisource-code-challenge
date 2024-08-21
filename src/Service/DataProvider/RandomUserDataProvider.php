<?php

namespace App\Service\DataProvider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RandomUserDataProvider implements DataProviderInterface
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchCustomers(int $count): array
    {
        $response = $this->client->request(
            'GET',
            'https://randomuser.me/api',
            ['query' => ['results' => $count, 'nat' => 'AU']]
        );

        return $response->toArray()['results'];
    }
}
