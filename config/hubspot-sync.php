<?php
return [

    /*
    |--------------------------------------------------------------------------
    | HubSpot Object Sync Configuration
    |--------------------------------------------------------------------------
    |
    | Define your table-to-HubSpot object mappings here.
    | Each mapping can specify:
    | - The model (your Laravel app's model)
    | - The HubSpot object type (e.g., contacts, deals)
    | - Field mappings
    | - Associations
    |
    */

    'sync_objects' => [

        'contacts' => [
            'model' => \App\Models\Contact::class,

            'fields' => [
                'email' => 'email',
                'first_name' => 'firstname',
                'last_name' => 'lastname',
            ],

            'conditions' => [
                ['hubspot_id', '=', null], // sync only unsynced
            ],

            'associations' => [
                'company' => [
                    'model' => \App\Models\Company::class,
                    'hubspot_object' => 'companies',
                    'foreign_key' => 'company_id',
                ]
            ],
        ],

        'deals' => [
            'model' => \App\Models\Deal::class,

            'fields' => [
                'title' => 'dealname',
                'amount' => 'amount',
            ],

            'conditions' => [
                ['synced_at', '=', null],
            ],
        ],

    ],
    'hubspot' => [
        'token'    => env('HUBSPOT_ACCESS_TOKEN'),
        'base_url' => env('HUBSPOT_BASE_URL', 'https://api.hubapi.com'),
    ]
];