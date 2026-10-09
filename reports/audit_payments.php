<?php
$assignments = \App\Models\ProjectAssignment::with('user', 'project', 'payments')->get();

foreach ($assignments as $a) {
    echo "=== " . ($a->user->name ?? 'N/A') . " | " . ($a->project->title ?? 'N/A') . " ===\n";
    echo "  ID:" . $a->id . " Contract:" . number_format($a->contract_amount) . " Paid:" . number_format($a->amount_paid) . " Outstanding:" . number_format($a->outstanding_balance) . "\n";
    echo "  Payment records: " . $a->payments->count() . "\n";
    foreach ($a->payments as $p) {
        echo "    REF:" . $p->reference . " | AMT:" . number_format($p->amount) . " | " . $p->status . " | " . optional($p->paid_at)->format('Y-m-d') . "\n";
    }
    echo "\n";
}
