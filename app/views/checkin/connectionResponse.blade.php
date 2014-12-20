@extends('layouts.master')

@section('content')
	<h1>This is where i echo out hte response from the connection attempt</h1>
	
	@if ( $response == true )
		<p>You have successfully connected to {{ User::find( $admin )->email }}</p>
	@else
		<p>An error occured please try again. {{ link_to('checkin/connect', 'You can try again here') }}</p>
	@endif

@stop