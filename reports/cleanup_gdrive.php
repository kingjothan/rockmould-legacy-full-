<?php
$deleted = \App\Models\MediaAsset::where('file_path', 'like', '%drive.google.com%')->delete();
echo "Removed {$deleted} Google Drive images.\n";
echo "Live updates count: " . \App\Models\MediaAsset::where('gallery_section', 'live-updates')->count() . "\n";
