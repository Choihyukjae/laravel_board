<?php
/********************
 * 프로젝트명 : larevel_board
 * 디렉토리   : Controllers
 * 파일명     : BoardsController.php
 * 이력       : v001 0526 최혁재 new
 *          
 */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\UserController;


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
Route::resource('/boards', BoardsController::class);

Route::get('/users/login',[UserController::class,'login'])->name('users.login');
Route::post('/users/loginpost',[UserController::class,'loginpost'])->name('users.login.post');
Route::get('/users/registration',[UserController::class, 'registration'])->name('users.registration');
Route::post('/users/registrationpost',[UserController::class, 'registrationpost'])->name('users.registration.post');
