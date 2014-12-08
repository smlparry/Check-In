@extends('layouts.master')

@section("content")
 	<?php $success = Session::get( 'success' ) ?>
	@if ( ! empty( $success ) )
		<div class="alert alert-success alert-block ">
			{{ $success }}
		</div>
	@endif
	<ul>
		<li>{{ link_to('login', 'Login') }}</li>
		<li>{{ link_to('dash', 'Dashboard') }}</li>
		<li>{{ link_to('users', 'Connected Users') }}</li>
		<li>{{ link_to('register', 'Register') }}</li>
		<li>{{ link_to('checkin/7BWgg4AK', 'Check In Page') }}</li>
		<li>{{ link_to('checkin/history', 'Check In Feed') }}</li>
		<li>{{ link_to('logout', 'Logout') }}</li>
		
		@if ( Auth::check() )
			{{ 'Logged in as: ' . Auth::user()->email . ' (' . Auth::user()->group_id . ')' }}
		@else
			{{ 'Not logged in' }}
		@endif
	</ul>
@stop