<?php
echo json_encode(
    \App\Models\Project::all(['id', 'title', 'category'])->toArray(),
    JSON_PRETTY_PRINT
);
