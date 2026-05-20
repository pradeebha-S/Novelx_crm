<?php



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiController;





Route::get('/user', function (Request $request) {

    return $request->user();

})->middleware('auth:sanctum');







Route::get('/getAllProjects',[ApiController::class,'getAllProjects']);
Route::get('/monthly-report', [ApiController::class, 'monthly_report']);

