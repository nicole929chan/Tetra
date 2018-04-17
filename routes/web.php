<?php
Route::get('project', function () {
	return 'project list page';
})->name('projects.index');

Route::get('rooms/{room}/versions/{version}', 'RoomsController@show')->name('rooms.show');
Route::get('rooms/{room}', 'RoomsController@show')->name('rooms.show.default');
Route::post('rooms/{room}', 'RoomsController@store')->name('rooms.store');

Route::get('rooms/{room}/selections', 'SelectionController@index')->name('selection.index');
Route::post('rooms/selections/{selection}', 'SelectionController@store')->name('selection.store');

Route::get('comments/versions/{version}', 'CommentsController@index')->name('comments.index');
Route::post('comments/versions/{version}', 'CommentsController@store')->name('comments.store');
Route::patch('comments/{comment}', 'CommentsController@update')->name('comments.update');
Route::delete('comments/{comment}', 'CommentsController@destroy')->name('comments.destroy');

Route::post('replies/comments/{comment}', 'RepliesController@store')->name('replies.store');
Route::patch('replies/{reply}', 'RepliesController@update')->name('replies.update');
Route::delete('replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

Route::get('marks/versions/{version}', 'MarksController@index')->name('marks.index');
Route::post('marks/versions/{version}', 'MarksController@store')->name('marks.store');
Route::patch('marks/{mark}', 'MarksController@update')->name('marks.update');
Route::delete('marks/{mark}', 'MarksController@destroy')->name('marks.destroy');

Route::get('activities/versions/{version}', 'ActivitiesController@index')->name('activities.index');

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
