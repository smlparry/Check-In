<?php

// Main Pages
Route::get("/dash", "PagesController@dashboard");
Route::get('/', 'PagesController@landing');

// Auth Routing
Route::resource("/login", "UsersController");
Route::get("/register", "UsersController@create");
Route::post("/register", "UsersController@register");
Route::get('/logout', 'UsersController@destroy');

// Check in Routing
Route::get('/checkin/history', 'CheckInController@history');
Route::get('/checkin/feed', 'CheckinController@feed');
Route::get('/checkin/{uniqueId}', 'CheckinController@index');
Route::post('/checkin', ['as' => 'checkUserIn', 'uses' => 'CheckinController@after'] );

// Admin operations
Route::get('/users', 'AdminController@connectedUsers');

/*
	Random testing!
 */
Route::get('/test', 'TestController@index');