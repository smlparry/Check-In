@extends('layouts.master')

@section('content')
	<h1>This is where the admin specifies what information they require from their connected users</h1>

	@if ( $requiredDetails !== false )
		{{ Form::open(['route' => 'storeRequiredDetails', 'class' => 'form-horizontal'])}}
			<!-- Require Name -->
			{{ Form::label('Name') }}
			{{ Form::checkbox( 'require_name', '', $requiredDetails->name )}}
			<!-- Require address -->
			{{ Form::label('Address') }}
			{{ Form::checkbox('require_address', '', $requiredDetails->address) }}
			<!-- Require postcode -->
			{{ Form::label('Postcode') }}
			{{ Form::checkbox('require_postcode', '', $requiredDetails->postcode) }}
			<!-- Require phone number -->
			{{ Form::label('Phone Number') }}
			{{ Form::checkbox('require_phone_number', '', $requiredDetails->phone_number) }}
			<!-- Require custom details -->
			{{ Form::label('Extra Details') }}
			@foreach ( $requiredDetails->custom_details_data as $customDetail )
				{{ Form::label( $customDetail ) }}
				{{ Form::checkbox( 'require_' . $customDetail, '', true ) }}
			@endforeach
		{{ Form::close() }}
	@else
		<p> You must be an admin to perform this function </p>
	@endif
@stop