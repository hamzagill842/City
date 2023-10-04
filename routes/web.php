<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('send-mail', function () {

    try {
        $details = [

            'title' => 'Mail from ItSolutionStuff.com',

            'body' => 'This is for testing email using smtp'

        ];



        \Mail::to('hamzagill415@gmail.com')->send(new \App\Mail\MyTestMail($details));
        dd("Email is Sent.");

    } catch ( Exception $exception) {
        dd($exception);
    }





});
