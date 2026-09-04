<?php
// Runs inside the already-bootstrapped Laravel app (via deploy webhook)
use App\Models\TimelineEntry;

TimelineEntry::query()->delete();

$entries = [
    ['year' => '2021 — THE BEGINNING',          'title' => 'Commenced Operations',                   'description' => 'Rockmould began its development journey with DEJ Apartments in Lekki Phase 1.',                                                                                                                              'sort_order' => 1, 'is_visible' => true],
    ['year' => '2022 — FIRST LANDMARK COMPLETION','title' => 'Delivered Our Inaugural Development',    'description' => 'Completed DEJ Apartments, Lekki Phase 1—our first 32-unit residential development.',                                                                                                                        'sort_order' => 2, 'is_visible' => true],
    ['year' => '2023 — PORTFOLIO EXPANSION',      'title' => 'Expanded Across Prime Lagos Locations',  'description' => 'Commenced three residential developments across Lekki Phase 1, Victoria Island and Ikoyi.',                                                                                                                 'sort_order' => 3, 'is_visible' => true],
    ['year' => '2024 — A YEAR OF DELIVERY',       'title' => 'Completed Three Landmark Developments', 'description' => 'Delivered DEJ Apartments Victoria Island, The Rock Apartments Lekki and The One Villa Ikoyi—a combined 56 residential units.',                                                                             'sort_order' => 4, 'is_visible' => true],
    ['year' => '2025 — PREMIUM PIPELINE GROWTH',  'title' => 'Broadened Our Development Portfolio',   'description' => 'Expanded the portfolio with new developments across Ikoyi, Ikate and Lekki, including Stainless Point, Meridian, Valerian and Carlton Residences.',                                                         'sort_order' => 5, 'is_visible' => true],
    ['year' => '2026 — BUILDING AT GREATER SCALE','title' => 'Advancing a ₦163.9 Billion + Portfolio','description' => 'Progressing a diversified portfolio of premium residential developments across Lagos.',                                                                                                                      'sort_order' => 6, 'is_visible' => true],
];

foreach ($entries as $e) {
    TimelineEntry::query()->create($e);
}

echo 'Done. ' . count($entries) . ' timeline entries seeded.';
