<?php

//use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

//rol para el admin
//$role = Role::create(['name' => 'admin']);

//rol para el cliente
//$role = Role::create(['name' => 'client']);

Route::get('/', function () {
    return view('welcome');
});
