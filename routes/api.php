<?php

use App\Http\Requests\LoginRequest;
use App\Util\myResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'UserController@loginApi');
Route::get('ots', 'OrdenTrabajoController@list');

Route::get('ots_personal', 'OrdenTrabajoPersonalController@listPersonal')->name('admin.ots_personal');
Route::get('personal_ots', 'OrdenTrabajoPersonalController@listotsporpersonal')->name('admin.personal_ots');
Route::get('ots_personal_disponible', 'OrdenTrabajoPersonalController@personal_disponible_por_ot')->name('admin.ots_personal');
Route::get('marcacion/registro', 'MarcacionController@registro')->name('marcacion.registroapi');

