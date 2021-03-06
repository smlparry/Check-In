<?php

// // Event listener
// Event::listen('illuminate.query', function($query){
// 	echo $query . PHP_EOL;
// });

// Main Pages
Route::get('/', 'PagesController@landing');
Route::get('/access-denied', 'PagesController@accessDenied');

// Api routing
Route::group(['prefix' => 'api/v1'], function(){

	// Check in routing
	Route::group(['before' => 'api.admin'], function(){
		Route::get('/checkin/feed', 'CheckinController@feed');
	});
	Route::group(['before' => 'api.auth'], function(){
		Route::get('/checkin/history', 'CheckInController@history');
		Route::get('/checkin/{uniqueId}', 'CheckinController@index');
		Route::post('/checkin', ['as' => 'checkUserIn', 'uses' => 'CheckinController@checkUserIn'] );
	});

	// Connection routing
	Route::group(['before' => 'api.auth'], function(){
		Route::get('/connections', 'ConnectionController@connections');
		Route::get('/connect', 'ConnectionController@connect');
		Route::post('/connect/connection-attempt', ['as' => 'addConnection', 'uses' => 'ConnectionController@addConnection'] );
		Route::post('/connect/add-details', ['as' => 'addDetails', 'uses' => 'ConnectionController@addDetails'] );
	});

	Route::group(['before' => 'api.admin'], function(){
		Route::get('admin/users', 'AdminController@connectedUsers');
		Route::get('admin/required-details', 'AdminController@getRequiredDetails');
		Route::post('admin/required-details', ['as' => 'storeRequiredDetails', 'uses' => 'AdminController@storeRequiredDetails']);
	});

});

// Auth Routing
Route::group(['before' => 'guest'], function(){
	Route::resource("/login", "UsersController");
	Route::get("/register", "UsersController@create");
	Route::post("/register", "UsersController@register");
});

Route::group(['before' => 'auth'], function(){
	Route::get('/logout', ['before' => 'auth', 'uses' => 'UsersController@destroy'] );
});


// Connection Routing
Route::group(['before' => 'auth'], function(){
	Route::get('/connect', 'ConnectionController@connect');
	Route::post('/connect/connection-attempt', ['as' => 'addConnection', 'uses' => 'ConnectionController@addConnection'] );
	Route::post('/connect/addRequiredDetails', ['as' => 'addRequiredDetails', 'uses' => 'ConnectionController@addRequiredDetails'] );
});
Route::group(['before' => 'hasErrors'], function() {
	Route::get('/connect/connection-attempt', 'ConnectionController@addConnection');
});


// Admin operations
Route::group(['before' => 'auth'], function(){
	Route::get("/dash", "AdminController@dashboard");
});
Route::group(['before' => 'admin'], function(){
	Route::get('/users', 'AdminController@connectedUsers');
	Route::get('/users/required-details', 'AdminController@getRequiredDetails');
	Route::post('/users/required-details', ['as' => 'storeRequiredDetails', 'uses' => 'AdminController@storeRequiredDetails']);
});

/*
	Random testing!
 */
Route::get('/test', 'TestController@index');