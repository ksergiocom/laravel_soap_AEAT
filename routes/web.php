<?php

use \App\Services\SoapService;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/soap/info', function(){
    $ss = new SoapService();
    return $ss->info();
});

Route::get('/soap/consulta', function(){
    $ss = new SoapService();
    return $ss->consultarFacturas();
});

Route::get('/soap/alta-primero', function(){
    $ss = new SoapService();
    return $ss->altaFactura();
});