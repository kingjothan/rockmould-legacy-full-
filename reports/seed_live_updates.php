<?php

$pid = \App\Models\Project::where('title', 'like', '%Meridian%')->value('id');
if (!$pid) { echo "Meridian project not found.\n"; return; }

$images = [
    ['title' => 'Maison 6 Live Update 1', 'file_path' => 'https://drive.google.com/uc?export=view&id=1YqC_jYoOh-sZrLLKRiHcC5Xu9vs750g2'],
    ['title' => 'Maison 6 Live Update 2', 'file_path' => 'https://drive.google.com/uc?export=view&id=1Z4KbRH8IaGpDyn2Ld4iUJFafOlVmVMot'],
    ['title' => 'Maison 6 Live Update 3', 'file_path' => 'https://drive.google.com/uc?export=view&id=1pVIugsVnKnuxK9d_Vu_ZzheZTofuKV6s'],
    ['title' => 'Maison 6 Live Update 4', 'file_path' => 'https://drive.google.com/uc?export=view&id=1pNyuIPVsx5vQX-Q1DLEv6UPpph8IzkcP'],
    ['title' => 'Maison 6 Live Update 5', 'file_path' => 'https://drive.google.com/uc?export=view&id=1g5X7PcsYKHwM1gTl_C7a0BmWdaNoNYyq'],
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
echo count($images) . " images added to Meridian Residence Live Updates.\n";
