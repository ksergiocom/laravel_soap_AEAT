<?php

use \App\Services\SoapService;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/soap-info', function(){
    $ss = new SoapService();
    return $ss->info();
});

Route::get('/soap-test', function(){
    $ss = new SoapService();
    return $ss->consultarFacturas();
});
