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

        $tripPath = 'import/files/trips.json';
        $catchPath = 'import/files/catches.json';
        $imagePath = 'import/files/images.json';

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

        $tripData = json_decode(Storage::disk('local')->get($tripPath), true);
        $catchData = json_decode(Storage::disk('local')->get($catchPath), true);
        $imageData = json_decode(Storage::disk('local')->get($imagePath), true);

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
                    'action' => $data['action'] ?? 'None'
                ]);
                $legacyToNewId[$data['legacy_id']] = $trip->id;
                $this->info("Imported trip legacy_id {$data['legacy_id']} -> trip id {$trip->id}");
            } catch (\Exception $e) {
                $this->error("Error importing trip at index {$index} with legacy_id {$data['legacy_id']}: " . $e->getMessage());
            }
        }

        // Import catches
        $catchCount = 0;
        foreach ($catchData as $index => $data) {
            $legacyId = $data['legacy_id'] ?? null;
            if (!$legacyId) {
                $this->warn("Skipping catch at index {$index}: Missing legacy_id");
                continue;
            }

            if (!isset($legacyToNewId[$legacyId])) {
                $this->warn("Skipping catch at index {$index}: legacy_id '{$legacyId}' not found in trips.");
                continue;
            }

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
                $catchCount++;
                $this->info("Imported catch for legacy_id {$legacyId} (trip_id: {$catch->trip_id})");
            } catch (\Exception $e) {
                $this->error("Error importing catch at index {$index}: " . $e->getMessage());
                $this->line("Raw data: " . json_encode($data));
            }
        }

        // Import images
        $imageCount = 0;
        foreach ($imageData as $index => $data) {
            $legacyId = $data['legacy_id'] ?? null;
            if (!$legacyId) {
                $this->warn("Skipping image at index {$index}: Missing legacy_id");
                continue;
            }

            if (!isset($legacyToNewId[$legacyId])) {
                $this->warn("Skipping image at index {$index}: legacy_id '{$legacyId}' not found in trips.");
                continue;
            }

            try {
                TripImage::create([
                    'trip_id' => $legacyToNewId[$legacyId],
                    'image_path' => $data['image_path'] ?? null,
                    'caption' => $data['caption'] ?? null,
                ]);
                $imageCount++;
                $this->info("Imported image for legacy_id {$legacyId}");
            } catch (\Exception $e) {
                $this->error("Error importing image at index {$index}: " . $e->getMessage());
                $this->line("Raw data: " . json_encode($data));
            }
        }

        $this->info("Import complete:");
        $this->info("Trips imported: " . count($legacyToNewId));
        $this->info("Catches imported: " . $catchCount);
        $this->info("Images imported: " . $imageCount);

        return 0;
    }
}