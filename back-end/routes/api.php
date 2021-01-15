<?php


use App\Http\Controllers\CategoryController;

use App\Http\Controllers\QuizController;

use App\Http\Controllers\ResultController;


use App\Http\Controllers\RoleUserController;

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



Route::post('login', [UserController::class, 'authenticate']);
Route::post('register', [UserController::class, 'register']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('jwt.verify')->group(function () {
    //user
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::get('users/getId/{key}', [UserController::class, 'getIdByEmail']);

    Route::get('users/role/{email}', [UserController::class, 'getRole']);

    Route::put('role-user/{id}', [RoleUserController::class, 'update']);


    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('quizzes')->group(function () {
        Route::get('/', [QuizController::class, 'index']);
        Route::post('/', [QuizController::class, 'store']);
        Route::get('/{id}', [QuizController::class, 'show']);
        Route::put('/{id}', [QuizController::class, 'update']);
        Route::delete('/{id}', [QuizController::class, 'destroy']);
        Route::get('/quizzes/showByCategoryId/{id}', [QuizController::class, 'showQuizByCategoryId']);
        Route::get('/search/{key}', [QuizController::class, 'searchByCategory']);

    });
    Route::prefix('test')->group(function () {
        Route::get('/{id}', [QuizController::class, 'showTest']);
    });

    //results
    Route::prefix('results')->group(function () {
        Route::post('/', [ResultController::class, 'store']);
        Route::get('/', [ResultController::class, 'index']);
        Route::delete('/{id}', [ResultController::class, 'delete']);
        Route::get('/{id}', [ResultController::class, 'showResultFindId']);
    });


});




