<?php

use Illuminate\Support\Facades\Route;
use App\Services\Midtrans\CreateSnapTokenService;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


Route::get('/bay', function () {
    $midtrans = new CreateSnapTokenService(
        [
            'name' => 'Rizky',
            'email' => 'reksa.prayoga1012@gmail.com',
            'phone' => '08123456789',
            'amount' => 10000
        ]
    );
    $snapToken = $midtrans->getSnapToken();
    return view('pay', ['snapToken' => $snapToken]);
});
