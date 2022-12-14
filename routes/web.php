<?php

declare(strict_types=1);

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\{Auth, Route};
use App\Http\Controllers\{ContactController, HomeController};

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

Route::get('/', fn (): Renderable => view('welcome'));

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('contact', [ContactController::class, 'send'])->name('contact');
