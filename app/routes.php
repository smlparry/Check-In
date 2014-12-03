<?php

// Index/landing page
Route::get("/", "HomeController@showWelcome");

// Auth Routing
Route::resource("/login", "UsersController");
Route::get("/register", "UsersController@create");
Route::post("/register", "UsersController@register");

// Should be for only logged in users
Route::get("/dash", "PagesController@dashboard");
