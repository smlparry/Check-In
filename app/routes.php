<?php

// Main Pages
Route::get('/', 'PagesController@landing');
Route::get('/access-denied', 'PagesController@accessDenied');

// Auth Routing
Route::resource("/login", "UsersController");

Route::group(['before' => 'guest'], function(){
	Route::get("/register", "UsersController@create");
	Route::post("/register", "UsersController@register");
});

Route::get('/logout', ['before' => 'auth', 'uses' => 'UsersController@destroy'] );


// Check in Routing
Route::group(['before' => 'auth'], function(){
	Route::get('/checkin/history', 'CheckInController@history');
	Route::get('/checkin/feed', 'CheckinController@feed');
	Route::get('/checkin/connect', 'CheckinController@connect');
	Route::post('/checkin/connect', ['as' => 'addConnection', 'uses' => 'CheckinController@addConnection'] );
	Route::get('/checkin/{uniqueId}', 'CheckinController@index');
	Route::post('/checkin', ['as' => 'checkUserIn', 'uses' => 'CheckinController@after'] );
});

// Admin operations
Route::group(['before' => 'admin'], function(){
	Route::get("/dash", "AdminController@dashboard");
	Route::get('/users', 'AdminController@connectedUsers');
	Route::get('/users/required-details', 'AdminController@getRequiredDetails');
	Route::post('/users/required-details', ['as' => 'storeRequiredDetails', 'uses' => 'AdminController@storeRequiredDetails']);
});

/*
	Random testing!
 */
Route::get('/test', 'TestController@index');