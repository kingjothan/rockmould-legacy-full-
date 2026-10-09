<?php

$emails = [
    'siabulu@gmail.com',
    'adama_ahmed@yahoo.com',
    'arike.dairo@pokameil.com',
    'sylvanchukwu@gmail.com',
    'adedamolakazeem@yahoo.com',
    'adewuyihafeez@gmail.com',
    'ovensehi@gmail.com',
    'profoskie27@gmail.com',
    'gregosau88@gmail.com',
    'ahmeddotun23@yahoo.com',
    'ahmazauto@gmail.com',
    'seprebo@yahoo.com',
    'danedoho@gmail.com',
    'nnamaniroman@gmail.com',
    'imasuen_osagie@hotmail.com',
    'adebayo@fragvest.com',
    'ezinohomeslimited@gmail.com',
    'gabi.llc01@gmail.com',
    'mikeogb1@gmail.com',
    'diversepartners2@gmail.com',
    'sunclairelp@gmail.com',
];

$added = 0;
foreach ($emails as $email) {
    \App\Models\NewsletterSubscriber::firstOrCreate(
        ['email' => strtolower(trim($email))],
        ['status' => 'subscribed', 'subscribed_at' => now()]
    );
    $added++;
}
echo $added . " emails processed. Total: " . \App\Models\NewsletterSubscriber::where('status', 'subscribed')->count() . "\n";
