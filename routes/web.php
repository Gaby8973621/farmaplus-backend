<?php

//use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
//use Spatie\Permission\Models\Role;


//rol para el admin
//$role = Role::create(['name' => 'admin']);

//rol para el cliente
//$role = Role::create(['name' => 'client']);

Route::get('/', function () {
    return view('welcome');
});
