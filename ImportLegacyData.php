<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use App\Models\Catches;
use App\Models\TripImage;
use Illuminate\Support\Facades\Storage;

class ImportLegacyData extends Command
{
    protected $signature = 'legacy:import';
    protected $description = 'Import trips, catches, and images from legacy JSON data';

    public function handle()
    {
        $this->info('Starting import...');

        // Define paths relative to storage/app
        $tripPath = 'import/files/trips.json';
        $catchPath = 'import/files/catches.json';
        $imagePath = 'import/files/images.json';

        // Check if files exist
        if (!Storage::disk('local')->exists($tripPath)) {
            $this->error("Missing file: $tripPath");
            return 1;
        }
        if (!Storage::disk('local')->exists($catchPath)) {
            $this->error("Missing file: $catchPath");
            return 1;
        }
        if (!Storage::disk('local')->exists($imagePath)) {
            $this->error("Missing file: $imagePath");
            return 1;
        }


        $tripDataRaw = Storage::disk('local')->get($tripPath);
        $catchDataRaw = Storage::disk('local')->get($catchPath);
        $imageDataRaw = Storage::disk('local')->get($imagePath);

        $tripData = json_decode($tripDataRaw, true);
        $catchData = json_decode($catchDataRaw, true);
        $imageData = json_decode($imageDataRaw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON in one or more files: " . json_last_error_msg());
            return 1;
        }

        if (!is_array($tripData) || !is_array($catchData) || !is_array($imageData)) {
            $this->error("One or more JSON files did not decode to an array.");
            return 1;
        }

        $legacyToNewId = [];

        // Import trips
        foreach ($tripData as $index => $data) {
            if (!isset($data['legacy_id'])) {
                $this->warn("Skipping trip at index {$index} due to missing legacy_id.");
                continue;
            }

            try {
                $trip = Trip::create([
                    'user_id' => 1,
                    'title' => $data['title'] ?? null,
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null,
                    'location' => $data['location'] ?? null,
                    'date' => $data['date'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'precipitation' => is_numeric($data['precipitation'] ?? null) ? $data['precipitation'] : null,
                    'moon_phase' => $data['moon_phase'] ?? null,
                    'wind_speed' => is_numeric($data['wind_speed'] ?? null) ? $data['wind_speed'] : null,
                    'wind_direction' => $data['wind_direction'] ?? null,
                    'air_temp' => is_numeric($data['air_temp'] ?? null) ? $data['air_temp'] : null,
                ]);
                $legacyToNewId[$data['legacy_id']] = $trip->id;
            } catch (\Exception $e) {
                $this->error("Error importing trip at index {$index} with legacy_id {$data['legacy_id']}: " . $e->getMessage());
            }
        }

        foreach ($catchData as $index => $entry) {
            $legacyId = $entry['legacy_id'] ?? null;
            if (!$legacyId) {
                $this->warn("Skipping catch entry at index {$index}: Missing 'legacy_id'.");
                continue;
            }
            if (!isset($legacyToNewId[$legacyId])) {
                $this->warn("Skipping catch entry at index {$index}: legacy_id '{$legacyId}' not found in trips.");
                continue;
            }

            $catches = $entry['catches'] ?? [];
            if (!is_array($catches)) {
                $this->warn("Skipping catch entry at index {$index}: 'catches' is not an array.");
                continue;
            }

            foreach ($catches as $catchIndex => $data) {
                try {
                    $catch = Catches::create([
                        'trip_id' => $legacyToNewId[$legacyId],
                        'species' => $data['species'] ?? null,
                        'weight' => is_numeric($data['weight'] ?? null) ? $data['weight'] : null,
                        'length' => is_numeric($data['length'] ?? null) ? $data['length'] : null,
                        'quantity' => is_numeric($data['quantity'] ?? null) ? $data['quantity'] : null,
                        'bait' => $data['bait'] ?? null,
                        'depth' => $data['depth'] ?? null,
                        'water_temp' => is_numeric($data['water_temp'] ?? null) ? $data['water_temp'] : null,
                        'notes' => $data['notes'] ?? null,
                    ]);
                    $this->info("Imported catch id {$catch->id} for trip {$legacyToNewId[$legacyId]}");
                } catch (\Exception $e) {
                    $this->error("Error importing catch at catchIndex {$catchIndex} in entry index {$index} with legacy_id {$legacyId}: " . $e->getMessage());
                }
            }
        }

        foreach ($imageData as $index => $entry) {
            $legacyId = $entry['legacy_id'] ?? null;
            if (!$legacyId) {
                $this->warn("Skipping image entry at index {$index}: Missing 'legacy_id'.");
                continue;
            }
            if (!isset($legacyToNewId[$legacyId])) {
                $this->warn("Skipping image entry at index {$index}: legacy_id '{$legacyId}' not found in trips.");
                continue;
            }

            $images = $entry['images'] ?? [];
            if (!is_array($images)) {
                $this->warn("Skipping image entry at index {$index}: 'images' is not an array.");
                continue;
            }

            foreach ($images as $imgIndex => $data) {
                try {
                    TripImage::create([
                        'trip_id' => $legacyToNewId[$legacyId],
                        'image_path' => $data['image_path'] ?? null,
                        'caption' => $data['caption'] ?? null,
                    ]);
                } catch (\Exception $e) {
                    $this->error("Error importing image at imgIndex {$imgIndex} in entry index {$index} with legacy_id {$legacyId}: " . $e->getMessage());
                }
            }
        }

        $this->info("Import complete: " . count($tripData) . " trips, " . count($catchData) . " catch entries, " . count($imageData) . " image entries.");
        return 0;
    }
}