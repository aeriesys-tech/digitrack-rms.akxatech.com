<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-test-email', function () {
    Mail::raw('This is a test email from RMS.', function ($message) {
        $message->to('bharatesh.s@akxatech.com','raghuraj.rao@akxatech.com','alokmoy.bose@akxatech.com')->subject('Test Email from RMS');
    });

    return 'Test email sent!';
});