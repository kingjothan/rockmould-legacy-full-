<?php
use App\Models\MediaAsset;

$ids = [142, 143, 144, 146];
$fixed = 0;

foreach ($ids as $id) {
    $asset = MediaAsset::find($id);
    if (! $asset) { echo "ID {$id} not found.\n"; continue; }
    $old = $asset->getRawOriginal('title');
    $asset->updateQuietly(['title' => 'Interior']);
    echo "Fixed ID {$id}: \"{$old}\" → \"Interior\"\n";
    $fixed++;
}

echo "Done. Fixed {$fixed} captions.";
