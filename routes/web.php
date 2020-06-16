<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Auth\Middleware\Authenticate;
use \App\Http\Middleware\CheckIsAdmin;

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

Route::get('/', 'ViewController@home')->name('home');
Route::get('/login', 'ViewController@login')->name('login');

Route::middleware([Authenticate::class])->group(function () {
    Route::middleware([CheckIsAdmin::class])->group(function () {
        Route::get('/manage-user', 'UserController@manageUser')->name('manage-user');
        Route::get('/manage-book-type', 'BookTypeController@manageBookType')->name('manage-book-type');
        Route::get('/manage-classroom', 'ClassRoomController@manageClassroom')->name('manage-classroom');
        Route::get('/delete-book/{id}', 'BookController@deleteBook')->name('delete-book');
        Route::get('/set-book-private/{id}', 'BookController@setBookPrivate')->name('set-book-private');
    });

    Route::get('/manage-my-book', 'BookController@manageMyBook')->name('manage-my-book');
    Route::get('/add-my-book', 'BookController@addMyBook')->name('add-my-book');
    Route::post('/add-my-book-action', 'BookController@addMyBookAction')->name('add-my-book');
    Route::get('/edit-my-book/{id}', 'BookController@editMyBook')->name('edit-my-book');
    Route::get('/delete-my-book/{id}', 'BookController@deleteMyBook')->name('delete-my-book');
    Route::post('/edit-my-book-action/{id}', 'BookController@editMyBookAction')->name('edit-my-book-action');
    Route::get('/manage-profile', 'UserController@manageProfile')->name('manage-profile');

    Route::get('/view-my-class', 'ClassRoomController@viewMyClass')->name('view-my-class');
    Route::get('/view-class/{id}', 'ClassRoomController@viewClassById')->name('view-class');
});

Route::get( '/download-file/{any}', function($url){
    $file_url = storage_path("app/public/{$url}");

    return response()->download($file_url);
})->where('any', '.*');

Route::get('/book-detail/{book_id}', 'BookController@bookDetail')->name('book-detail');

Route::post('/auth/login', 'UserController@login')->name('login-action');
Route::get('/auth/logout', 'UserController@logout')->name('logout-action');

Route::get('/user', 'UserController@get');
Route::post('/user/create', 'UserController@create')->name('create-user-action');
Route::get('/user/find/{id}', 'UserController@find');
Route::post('/user/update/{id}', 'UserController@update');
Route::get('/user/delete/{id}', 'UserController@delete');
Route::post('/user/edit-password', 'UserController@editPassword')->name("edit-password");
Route::post('/edit-profile', 'UserController@editProfile')->name('edit-profile');

Route::get('/book', 'BookController@get');
Route::post('/book/create', 'BookController@create');
Route::get('/book/find/{id}', 'BookController@find');
Route::post('/book/update/{id}', 'BookController@update');
Route::get('/book/delete-my-book/{id}', 'BookController@deleteMyBook');

Route::get('/book-type', 'BookTypeController@get');
Route::post('/book-type/create', 'BookTypeController@create')->name('create-book-type-action');
Route::get('/book-type/find/{id}', 'BookTypeController@find');
Route::post('/book-type/update/{id}', 'BookTypeController@update');
Route::get('/book-type/delete/{id}', 'BookTypeController@delete');

Route::get('/classroom', 'ClassRoomController@get');
Route::post('/classroom/create', 'ClassRoomController@create')->name('create-classroom-action');
Route::get('/classroom/find/{id}', 'ClassRoomController@find');
Route::post('/classroom/update/{id}', 'ClassRoomController@update');
Route::get('/classroom/delete/{id}', 'ClassRoomController@delete');

Route::post("/add-book-comment", "BookController@addBookComment")->name('add-book-comment');
Route::get("/rate-book", "BookController@rateBook");
