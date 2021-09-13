<?php

use App\DotaModels\Genders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return view("welcome");
});

Route::get("/testinsertion", function () {
    $femaleGender = new Genders();
    $femaleGender->gender = "female";
    $femaleGender->save();
    return "a";
});

Auth::routes();

Route::get("/home", "HomeController@index")->name("home");
