<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-test-email', function () {
    Mail::raw('This is a test email from digiTRACK.', function ($message) {
        $message->to('bharatesh.s@akxatech.com','amey.desai@akxatech.com','sammed@aeriesys.com')->subject('Test Email from digiTRACK');
    });

    return 'Test email sent!';
});