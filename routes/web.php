<?php
Route::get('project', function () {
	return 'project list page';
})->name('projects.index');

Route::get('rooms/{room}/selections', 'SelectionController@index')->name('selection.index');
Route::post('rooms/selections/{selection}', 'SelectionController@store')->name('selection.store');

// Route::get('/selection', function () {
//     return view('selection');
// });

Route::get('/room', function () {
    return view('room');
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
