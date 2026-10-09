<?php

// Remove the broken Google Drive images
$deleted = \App\Models\MediaAsset::where('gallery_section', 'live-updates')
    ->where('title', 'like', 'Maison 6%')
    ->delete();
echo "Removed {$deleted} broken images.\n";

// Find Masion 6 project
$pid = \App\Models\Project::where('title', 'like', '%Masion%')->value('id');
if (!$pid) { echo "Masion 6 not found.\n"; return; }

$images = [
    ['title' => 'Masion 6 Live Update 1', 'file_path' => 'https://ik.imagekit.io/oseremen/Masion%206%20live%201.png'],
    ['title' => 'Masion 6 Live Update 2', 'file_path' => 'https://ik.imagekit.io/oseremen/Masion%206%20live0png'],
];

foreach ($images as $i => $img) {
    \App\Models\MediaAsset::create([
        'project_id' => $pid,
        'title' => $img['title'],
        'file_path' => $img['file_path'],
        'media_type' => 'image',
        'gallery_section' => 'live-updates',
        'is_featured' => false,
        'visible_to_client' => true,
        'sort_order' => $i + 1,
    ]);
}
echo count($images) . " images added.\n";
