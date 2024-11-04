<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BorrowBookController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login',[LoginController::class,'login']);
Route::post('/register',[UserController::class,'store']);

Route::middleware('auth:api')->group(function () {
    //user routes
    Route::post('/logout',action: [LoginController::class,'logout']);
    Route::put('/update/{userUuid}',[UserController::class,'update'])->middleware('can:user-update');
    Route::delete('/delete/{userUuid}',action: [UserController::class,'destroy'])->middleware('can:user-delete');
    Route::get('/user/{userUuid}',action: [UserController::class,'show'])->middleware('can:user-view');
    Route::get('/userall',action: [UserController::class,'index'])->middleware('can:user-list');
    Route::get('/usersearch',action: [UserController::class,'search']);

    // book routes
    Route::get('/book', [BookController::class, 'index'])->middleware('can:book-list');
    Route::post('/book', [BookController::class, 'store'])->middleware('can:book-create');
    Route::get('/book/{bookUuid}', [BookController::class, 'show'])->middleware('can:book-view');
    Route::put('/book/{bookUuid}', [BookController::class, 'update'])->middleware('can:book-update');
    Route::delete('/book/{book}', [BookController::class, 'destroy'])->middleware('can:book-delete');
    Route::get('/booksearch', [BookController::class, 'search']);
    Route::post('/bookimport', [BookController::class, 'import'])->middleware('can:book-create');
    Route::get('/bookexport', [BookController::class, 'export'])->middleware('can:book-export');

    // borrowing record routes
    Route::post('/borrow', [BorrowBookController::class, 'store']);
    Route::get('/borrowlist', [BorrowBookController::class, 'show']);
    Route::get('/borrowhistory', [BorrowBookController::class, 'history']);
    Route::put('/borrowreturn/{borrowUuid}', [BorrowBookController::class, 'update']);
    Route::get('/borrowedbooks/{bookUuid}', [BorrowBookController::class, 'borrowedBooks']);
    Route::get('/borrowedsearch', [BorrowBookController::class, 'search']);

    //activity log routes
    Route::get('/activitylog', [ActivityLogController::class, 'index']);
  });


