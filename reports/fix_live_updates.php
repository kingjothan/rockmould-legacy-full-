<?php

// Find the Masion 6 project
$masion = \App\Models\Project::where('title', 'like', '%Masion%')->first();
if (!$masion) {
    echo "Masion 6 project not found.\n";
    return;
}

// Move all live update images that were wrongly assigned
$updated = \App\Models\MediaAsset::where('gallery_section', 'live-updates')
    ->where('title', 'like', 'Maison 6%')
    ->update(['project_id' => $masion->id]);

echo "Moved {$updated} images to Masion 6 (ID: {$masion->id}).\n";
echo "Total live updates for Masion 6: " . \App\Models\MediaAsset::where('project_id', $masion->id)->where('gallery_section', 'live-updates')->count() . "\n";
