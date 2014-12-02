<?php

Route::get("/", "HomeController@showWelcome");
Route::get("/login", "UsersController@create");
Route::get("/users", "UsersController@index");
Route::get("/dash", "PagesController@dashboard");