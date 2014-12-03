<?php

// Main Pages
Route::get("/dash", "PagesController@dashboard");
Route::get('/', 'PagesController@landing');

// Auth Routing
Route::resource("/login", "UsersController");
Route::get("/register", "UsersController@create");
Route::post("/register", "UsersController@register");

// Check in Routing
Route::get('/checkin', 'CheckinController@index');
Route::post('/checkin', 'CheckinContoller@after');

/*
	Delete this one 
 */ 
Route::get('/users', 'UsersController@show');

