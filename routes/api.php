<?php


use App\Http\Controllers\User\Auth\OtpController;
use App\Http\Controllers\User\Auth\SignInController;
use App\Http\Controllers\User\Auth\SignupController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('user')->group(function () {
    Route::post('sign-up', [SignupController::class, 'signup']);
    Route::post('sign-in', [SignInController::class, 'signIn']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('otp')->middleware('check_otp_verify')->group(function () {
        Route::post('send-otp', [OtpController::class, 'sendOtp']);
        Route::post('otp-verify', [OtpController::class, 'verify']);
    });
});
