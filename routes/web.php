<?php

use App\Models\Candidate;
use App\Notifications\TestNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/qr', function () {
    return view('qr', [
        'candidates' => Candidate::paginate()
    ]);
});

Route::get('/dl/{url}', function ($url) {
    Storage::download('public/'.$url);

    return view('t');
})->name('download');
