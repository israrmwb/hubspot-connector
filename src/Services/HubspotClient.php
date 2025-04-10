<?php

namespace IsrarMWB\HubspotConnector\Services;

use GuzzleHttp\Client;

class HubspotClient
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('hubspot-sync.hubspot.base_url'),
            'headers' => [
                'Authorization' => 'Bearer ' . config('hubspot-sync.hubspot.token'),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ]
        ]);
    }

    public function postObject(string $objectType, array $data)
    {
        $response = $this->client->post("/crm/v3/objects/{$objectType}", [
            'json' => $data,
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function createAssociation(string $fromType, $fromId, string $toType, $toId)
    {
        // Use correct associationTypeId for each object
        $this->client->put("/crm/v3/objects/{$fromType}/{$fromId}/associations/{$toType}/{$toId}/3");
    }
}
