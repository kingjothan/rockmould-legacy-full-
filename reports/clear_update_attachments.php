<?php

$updated = \App\Models\ProjectUpdate::query()->whereNotNull('attachments')->update(['attachments' => '[]']);
echo "Cleared attachments from {$updated} updates.\n";
