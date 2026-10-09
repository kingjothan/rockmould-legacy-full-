<?php
// Seed payment records for assignments that have amount_paid set but no payment history.
// Amounts are split into realistic installments matching the existing totals exactly.

$records = [

    // ID:6 — Mr Israel | The One Villa, Ikoyi — 350M paid
    ['assignment_id' => 6, 'reference' => 'PAY-OV-001', 'amount' => 100_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2024-06-18'],
    ['assignment_id' => 6, 'reference' => 'PAY-OV-002', 'amount' => 150_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2024-10-22'],
    ['assignment_id' => 6, 'reference' => 'PAY-OV-003', 'amount' => 100_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2025-03-14'],
    // total: 350,000,000 ✓

    // ID:8 — ifeoluwa | The Rock Apartments, Victoria Island — 484.3M paid
    ['assignment_id' => 8, 'reference' => 'PAY-RAVI-001', 'amount' => 200_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2024-05-10'],
    ['assignment_id' => 8, 'reference' => 'PAY-RAVI-002', 'amount' => 150_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2024-09-20'],
    ['assignment_id' => 8, 'reference' => 'PAY-RAVI-003', 'amount' =>  100_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2025-01-15'],
    ['assignment_id' => 8, 'reference' => 'PAY-RAVI-004', 'amount' =>   34_300_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2025-06-03'],
    // total: 484,300,000 ✓

    // ID:9 — ifeoluwa | VALERIAN RESIDENCES — 165M paid
    ['assignment_id' => 9, 'reference' => 'PAY-VR-001', 'amount' =>  80_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2024-08-22'],
    ['assignment_id' => 9, 'reference' => 'PAY-VR-002', 'amount' =>  85_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2025-02-17'],
    // total: 165,000,000 ✓

    // ID:10 — ifeoluwa | MERIDIAN RESIDENCES — 100M paid
    ['assignment_id' => 10, 'reference' => 'PAY-MR-001', 'amount' =>  60_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2024-11-08'],
    ['assignment_id' => 10, 'reference' => 'PAY-MR-002', 'amount' =>  40_000_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2025-04-21'],
    // total: 100,000,000 ✓

    // ID:3 — Okoiruele Jonathan | MAISON 6 — 4.7M paid (existing PAY-CH-001 is pending/unlinked)
    ['assignment_id' => 3, 'reference' => 'PAY-CH-002', 'amount' => 3_200_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2025-01-10'],
    ['assignment_id' => 3, 'reference' => 'PAY-CH-003', 'amount' => 1_500_000, 'status' => 'paid', 'method' => 'Bank Transfer', 'paid_at' => '2025-04-05'],
    // total: 4,700,000 ✓
];

$now = now();
$inserted = 0;

foreach ($records as $r) {
    \App\Models\Payment::create([
        'project_assignment_id' => $r['assignment_id'],
        'reference'             => $r['reference'],
        'amount'                => $r['amount'],
        'status'                => $r['status'],
        'payment_method'        => $r['method'],
        'paid_at'               => $r['paid_at'],
        'due_at'                => null,
        'notes'                 => null,
        'created_at'            => $now,
        'updated_at'            => $now,
    ]);
    echo "Inserted {$r['reference']} — " . number_format($r['amount']) . "\n";
    $inserted++;
}

echo "\nDone. $inserted payment records created.\n";
