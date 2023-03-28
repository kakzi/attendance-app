<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmploymentController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('auth.login');
});

/**
 * route for admin
 */

//group route with prefix "admin"
Route::prefix('admin')->group(function () {

    //group route with middleware "auth"
    Route::group(['middleware' => 'auth'], function() {

        //route dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

        Route::resource('/office', OfficeController::class,['as' => 'admin']);
        Route::resource('/employment', EmploymentController::class,['as' => 'admin']);

        Route::get('/report', [ReportController::class, 'index'])->name('admin.report.index');
        Route::get('/report/filter', [ReportController::class, 'filter'])->name('admin.report.filter');
        Route::get('/report/download', [ReportController::class, 'download'])->name('admin.report.download');

    });
});