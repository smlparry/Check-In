@extends('layouts.master')

@section('content')
	<h1>This is where I respond to the updated required details</h1>
	
	@if ( $response === true )
		<p>You have successfully updated your required details, users will now need to meet your required details before they can connect to your place</p>
		{{ link_to('users/required-details', 'Return') }}
	@else
		<p>Something went wrong when trying to update your required details, Please try again</p>
		{{ link_to('users/required-details', 'Return') }}
	@endif
@stop