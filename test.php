<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\NewRegistrationMail;
use App\Models\Contact;

$contact = Contact::first() ?? Contact::create([
    'name' => 'Test User',
    'phone' => '0921169999',
    'link' => 'facebook.com/test',
    'email' => 'tuananhmaiv2006@gmail.com',
    'service' => 'Tư vấn tích xanh',
    'message' => 'This is a test registration.',
    'status' => 'pending'
]);

echo "Sending test email to: " . config('mail.admin_receiver') . "...\n";
try {
    Mail::to(config('mail.admin_receiver', 'tuananhmaiv2006@gmail.com'))->send(new NewRegistrationMail($contact));
    echo "SUCCESS: Email sent successfully!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
