<?php

\App\Models\NewsletterSubscriber::query()->delete();

$emails = [
    'bode.kingfordhomes@gmail.com',
    'rextonhomesltd@gmail.com',
    'springflowproperties@gmail.com',
    'regiumrealtors@gmail.com',
    'jennifer_nwokoro@yahoo.com',
    'info.demaxon@gmail.com',
];

foreach ($emails as $email) {
    \App\Models\NewsletterSubscriber::create([
        'email' => strtolower(trim($email)),
        'status' => 'subscribed',
        'subscribed_at' => now(),
    ]);
}
echo count($emails) . " subscribers added. Total: " . \App\Models\NewsletterSubscriber::where('status', 'subscribed')->count() . "\n";
