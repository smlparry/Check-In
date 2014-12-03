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
		<li>{{ link_to('users', 'Users') }}</li>
		<li>{{ link_to('register', 'Register') }}</li>
		<li>{{ link_to('checkin/7BWgg4AK', 'Check In Page') }}</li>
		<li>{{ link_to('logout', 'Logout') }}</li>
	</ul>
@stop