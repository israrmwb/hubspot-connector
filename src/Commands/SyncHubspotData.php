<?php
namespace IsrarMWB\HubspotConnector\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SyncHubspotData extends Command
{
    protected $signature = 'hubspot:sync';
    protected $description = 'Sync Laravel data to HubSpot';

    public function handle()
    {
        $syncObjects = config('hubspot-sync.sync_objects');

        foreach ($syncObjects as $hubspotObject => $objectConfig) {
            $model = $objectConfig['model'];
            $fields = $objectConfig['fields'];
            $conditions = $objectConfig['conditions'] ?? [];

            $query = $model::query();
            foreach ($conditions as $condition) {
                $query->where(...$condition);
            }

            $records = $query->get();
            foreach ($records as $record) {
                $payload = [
                    'properties' => collect($fields)->mapWithKeys(fn($hubspotField, $localField) => [
                        $hubspotField => $record->{$localField},
                    ])->toArray()
                ];

                // Send to HubSpot (you’ll create this method)
                $response = app('hubspot.client')->postObject($hubspotObject, $payload);

                // Mark as synced
                $record->update([
                    'hubspot_id' => $response->id ?? null,
                    'synced_at' => now(),
                ]);

                // Handle associations
                if (!empty($objectConfig['associations'])) {
                    foreach ($objectConfig['associations'] as $assocKey => $assocConfig) {
                        $related = $assocConfig['model']::find($record->{$assocConfig['foreign_key']});
                        if ($related && $related->hubspot_id) {
                            app('hubspot.client')->createAssociation(
                                $hubspotObject,
                                $response->id,
                                $assocConfig['hubspot_object'],
                                $related->hubspot_id
                            );
                        }
                    }
                }

                $this->info("Synced {$model} ID {$record->id} to HubSpot.");
            }
        }
    }
}