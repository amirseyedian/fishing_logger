<?php
function checkLegacyId($entry, $index)
{
    // If entry is associative array and has legacy_id
    if (is_array($entry) && array_key_exists('legacy_id', $entry)) {
        return true;
    }
    // If entry is array of arrays, check each sub-entry
    if (is_array($entry)) {
        foreach ($entry as $subIndex => $subEntry) {
            if (!is_array($subEntry) || !array_key_exists('legacy_id', $subEntry)) {
                echo "Missing 'legacy_id' at index {$index}, sub-index {$subIndex}:\n";
                print_r($subEntry);
                echo "\n";
            }
        }
        return true; // we checked inside, so no need to print the outer entry
    }
    return false;
}

$jsonFile = 'storage/app/private/import/files/catches.json'; // your file path
$jsonData = file_get_contents($jsonFile);
$dataArray = json_decode($jsonData, true);

if ($dataArray === null) {
    die("Failed to decode JSON.\n");
}

foreach ($dataArray as $index => $entry) {
    if (!checkLegacyId($entry, $index)) {
        echo "Entry at index {$index} is missing 'legacy_id':\n";
        print_r($entry);
        echo "\n";
    }
}
?>