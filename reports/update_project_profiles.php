<?php
// Update remaining projects from Rockmould Project Portfolio document
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$data = [
    // The Rock Apartments, Lekki => The Vale Lekki
    2 => [
        'total_units'      => 32,
        'built_area'       => '6,132.07 sqm',
        'residence_profile'=> 'A multi-block residential development designed around modern urban functionality and institutional-grade construction standards. This apartment integrates premium finishes, leisure amenities, ample parking, and contemporary architecture within a secure and professionally managed residential environment.',
    ],
    // Masion 6, Ikoyi => Aurelia Residence
    3 => [
        'total_units'      => 32,
        'built_area'       => '9,999.06 sqm',
        'residence_profile'=> 'A premium terrace community crafted for refined family living in Ikoyi. Aurelia Residence combines elegant architectural design, private residential comfort, and long-term investment positioning within a secure and meticulously planned luxury residential setting.',
    ],
    // The Rock Apartments, Ikoyi (17 Glover Road) => Aster Residence
    7 => [
        'total_units'      => 8,
        'built_area'       => '2,000.02 sqm',
        'residence_profile'=> 'A low-density luxury residential development located on Glover Road, Ikoyi, offering an exclusive collection of expansive maisonettes and apartments designed for privacy, prestige, and long-term investment value within one of Lagos\' most desirable residential enclaves.',
    ],
    // The Rock Apartments, Victoria Island => Baroque Heights
    10 => [
        'total_units'      => 18,
        'built_area'       => '1,979.95 sqm',
        'residence_profile'=> 'A contemporary maisonette development positioned within Victoria Island\'s prestigious residential landscape. Designed with expansive terraces, premium lifestyle amenities, wellness facilities, elevators, and seamless indoor-outdoor living experiences tailored for sophisticated urban residents and investors.',
    ],
];

foreach ($data as $id => $fields) {
    $assigned = DB::table('project_assignments')->where('project_id', $id)->count();
    $available = max(0, $fields['total_units'] - $assigned);

    DB::table('projects')->where('id', $id)->update([
        'total_units'       => $fields['total_units'],
        'available_units'   => $available,
        'built_area'        => $fields['built_area'],
        'residence_profile' => $fields['residence_profile'],
        'updated_at'        => now(),
    ]);

    $title = DB::table('projects')->where('id', $id)->value('title');
    echo "OK ID {$id} ({$title}): {$fields['total_units']} total, {$available} available, {$fields['built_area']}\n";
}

echo "\nDone.\n";
