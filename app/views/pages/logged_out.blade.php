@extends('layouts.master')

@section("content")
	<ul>
		<li>{{ link_to('login', 'Login') }}</li>
		<li>{{ link_to('dash', 'Dashboard') }}</li>
		<li>{{ link_to('users', 'Users') }}</li>
		<li>{{ link_to('register', 'Register') }}</li>
	</ul>
@stop