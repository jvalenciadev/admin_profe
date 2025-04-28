<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\FrontendController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('v1')->group(function () {
    Route::middleware(['apikey'])->group(function () {
        Route::get('/eventos', [FrontendController::class, 'listEventos']);
        Route::get('/evento/{id}', [FrontendController::class, 'getEventoById']);
        Route::get('/programas', [FrontendController::class, 'listProgramas']);
        Route::get('/programa/{id}', [FrontendController::class, 'getProgramaById']);
        Route::get('/novedades', [FrontendController::class, 'listBlogs']);
        Route::get('/novedad/{id}', [FrontendController::class, 'getBlogById']);
        Route::get('/profe', [FrontendController::class, 'getProfe']);
        Route::get('/sedes', [FrontendController::class, 'listSedes']);
        Route::get('/sede/{id}', [FrontendController::class, 'getSedeById']);
        Route::get('/galerias', [FrontendController::class, 'listGalerias']);
        Route::get('/videos', [FrontendController::class, 'listVideos']);
        Route::get('/appInfo', [FrontendController::class, 'getAppInfo']);
    });
});
