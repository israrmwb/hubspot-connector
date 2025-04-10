# 🚀 Laravel HubSpot Connector

[![Latest Version](https://img.shields.io/packagist/v/israrmwb/hubspot-connector?label=version)](https://packagist.org/packages/israrmwb/hubspot-connector)
[![License](https://img.shields.io/github/license/israrmwb/hubspot-connector)](LICENSE)
[![Stars](https://img.shields.io/github/stars/israrmwb/hubspot-connector?style=social)](https://github.com/israrmwb/hubspot-connector)

A Laravel package that allows you to **sync data from your Laravel application's models into HubSpot CRM** using a **config-driven** approach.

> Define your sync rules once and automate your data pipeline to HubSpot — no custom logic needed in your app.

---

## 📦 Features

- ✅ Sync any Eloquent model to HubSpot objects (contacts, companies, deals, etc.)
- ⚙️ Config-driven object and field mapping
- 🔄 Supports conditional syncing (e.g., only unsynced records)
- 🔗 Automatic object associations (e.g., contact → company)
- 🛠️ Ready to extend and schedule

---

## 🧰 Requirements

- Laravel 10+
- PHP 8.1+
- HubSpot Private App Access Token

---

## 📥 Installation

Install the package via Composer:

```bash
composer require israrmwb/hubspot-connector
```

Then publish the configuration file:

```bash
php artisan vendor:publish --tag=hubspot-config
```

---

## ⚙️ Configuration

Update the published `config/hubspot-sync.php` to define what should be synced:

```php
return [

    'sync_objects' => [

        'contacts' => [
            'model' => \App\Models\Contact::class,
            'fields' => [
                'email'      => 'email',
                'first_name' => 'firstname',
                'last_name'  => 'lastname',
            ],
            'conditions' => [
                ['hubspot_id', '=', null],
            ],
            'associations' => [
                'company' => [
                    'model'          => \App\Models\Company::class,
                    'hubspot_object' => 'companies',
                    'foreign_key'    => 'company_id',
                ],
            ],
        ],

        'deals' => [
            'model' => \App\Models\Deal::class,
            'fields' => [
                'title'  => 'dealname',
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
    ],
];
```

---

## 🔐 Environment Variables

Add your HubSpot private app token to your `.env` file:

```env
HUBSPOT_ACCESS_TOKEN=your-hubspot-private-token
```

---

## 🚀 Usage

You can sync your data to HubSpot using the provided Artisan command:

```bash
php artisan hubspot:sync
```

This will loop through each configured object and:

- Query records based on conditions
- Map fields to HubSpot
- Send the data via the API
- Update the `hubspot_id` and `synced_at` in your database
- Optionally associate records (e.g., Contact to Company)

---

## 🪩 Supported HubSpot Objects

- Contacts
- Companies
- Deals  
(You can extend it for any CRM object via config.)

---

## 🧪 Example Use Case

You have contacts in your database and you want to:

- Only sync contacts where `hubspot_id` is null
- Map fields like `email`, `first_name`, `last_name`
- Automatically associate each contact with its related company

The config does it all — no need to write custom syncing logic.

---

## 🛠️ Scheduling

To run syncing periodically, add the command to your `app/Console/Kernel.php`:

```php
$schedule->command('hubspot:sync')->hourly();
```

---

## 📡 HubSpot SDK

This package uses [hubspot/api-client](https://github.com/HubSpot/hubspot-api-php) under the hood for full flexibility and future-proofing.

---

## 📆 Package Structure

```
src/
├── Commands/
│   └── SyncHubspotData.php
├── Services/
│   └── HubspotClient.php
├── HubspotConnectorServiceProvider.php
config/
└── hubspot-sync.php
```

---
---

## 📄 License

This package is open-sourced under the [MIT license](LICENSE).

---

## 🔗 Links

- [HubSpot Developer Docs](https://developers.hubspot.com/docs/api/overview)
- [Laravel Documentation](https://laravel.com/docs)
- [Packagist Package](https://packagist.org/packages/israrmwb/hubspot-connector)

