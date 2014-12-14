@extends('layouts.master')

@section("content")
	<ul>
		<li>{{ link_to('login', 'Login') }}</li>
		<li>{{ link_to('dash', 'Dashboard') }}</li>
		<li>{{ link_to('users', 'Connected Users') }}</li>
		<li>{{ link_to('register', 'Register') }}</li>
		<li>{{ link_to('checkin/7BWgg4AK', 'Check In Page') }}</li>
		<li>{{ link_to('checkin/history', 'Check In History') }}</li>
		<li>{{ link_to('checkin/feed', 'Check in Feed') }}</li>
		<li>{{ link_to('users/required-details', 'Required Details') }}</li>
		<li>{{ link_to('logout', 'Logout') }}</li>
		
		@if ( Auth::check() )
			{{ 'Logged in as: ' . Auth::user()->email  }}
			<br>
			{{ 'User id: ' . Auth::user()->id }}
			<br>
			{{ 'Group id: ' . Auth::user()->group_id }}
		@else
			{{ 'Not logged in' }}
		@endif
	</ul>
@stop