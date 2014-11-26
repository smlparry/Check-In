@extends('layouts.master')

@section("content")
	<ul>
		<li>{{ link_to('login', 'Login') }}</li>
		<li>{{ link_to('dash', 'Dashboard') }}</li>
		<li></li>
		<li></li>
	</ul>
@stop