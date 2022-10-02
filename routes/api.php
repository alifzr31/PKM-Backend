<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\HasilPengajuanController;
use App\Http\Controllers\Api\LapAkhirController;
use App\Http\Controllers\Api\LapkemController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\HasilReviewController;
use App\Http\Controllers\Api\LuaranController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
// Route::put('/editprofil/{id}', [UserController::class, 'update']);
Route::resource('/users', UserController::class);

Route::apiResource('/proposals', ProposalController::class);
Route::get('/proposalsbynip/{nip}', [ProposalController::class, 'proposalbynip']);
Route::post('/proposals/{id}', [ProposalController::class, 'update']);
Route::put('/statusproposal/{id}', [ProposalController::class, 'updatestatus']);
Route::get('/reviewproposal', [ProposalController::class, 'siapreview']);
Route::get('/seluruh', [ProposalController::class, 'seluruh']);
Route::get('/acc', [ProposalController::class, 'acc']);
Route::get('/dcc', [ProposalController::class, 'dcc']);
Route::get('/selesai', [ProposalController::class, 'selesai']);
Route::put('/catatan_revisi/{id}', [ProposalController::class, 'catatan_revisi']);

Route::get('/diterima/{nip}', [ProposalController::class, 'diterima']);

Route::apiResource('/lapkems', LapkemController::class);
Route::get('/lapkemsbynip/{nip}', [LapkemController::class, 'lapkembynip']);
Route::post('/lapkems/{id}', [LapkemController::class, 'update']);
Route::put('/editstatuslapkem/{id}', [LapkemController::class, 'editstatuslapkem']);

Route::apiResource('/lapakhirs', LapAkhirController::class);
Route::get('/lapakhirsbynip/{nip}', [LapAkhirController::class, 'lapakhirbynip']);
Route::post('/lapakhirs/{id}', [LapAkhirController::class, 'update']);
Route::put('/editstatuslapakhir/{id}', [LapAkhirController::class, 'editstatuslapakhir']);

Route::apiResource('/luarans', LuaranController::class);
Route::get('/luaransbynip/{nip}', [LuaranController::class, 'luaranbynip']);
Route::post('/luarans/{id}', [LuaranController::class, 'update']);
Route::put('/editstatusluaran/{id}', [LuaranController::class, 'editstatusluaran']);

Route::apiResource('/reviews', ReviewController::class);
Route::get('/reviewsbynip/{nip}', [ReviewController::class, 'reviewbynip']);
Route::post('/reviews/{id}', [ReviewController::class, 'update']);
Route::get('/belumdireview', [ReviewController::class, 'belumdireview']);
Route::put('/editstatusreview/{id}', [ReviewController::class, 'editstatusreview']);

Route::apiResource('/hasils', HasilReviewController::class);
Route::get('/hasilbynip/{nip}', [HasilReviewController::class, 'hasilbynip']);
Route::post('/hasil', [HasilReviewController::class, 'store']);
Route::post('/hasils/{id}', [HasilReviewController::class, 'update']);
Route::put('/editstatushasilreview/{id}', [HasilReviewController::class, 'editstatushasilreview']);
Route::get('/hasilterimadantolak', [HasilReviewController::class, 'hasilterimadantolak']);

Route::apiResource('/hasilpengajuans', HasilPengajuanController::class);
Route::get('/hasilpengajuanbystatus', [HasilPengajuanController::class, 'hasilpengajuanbystatus']);
Route::put('/editstatushasilpengajuan/{id}', [HasilPengajuanController::class, 'editstatushasilpengajuan']);