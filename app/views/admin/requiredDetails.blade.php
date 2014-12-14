@extends('layouts.master')

@section('content')
	<h1>This is where the admin specifies what information they require from their connected users</h1>

	@foreach( $requiredDetails as $requiredDetail )
		{{ Form::open(['route' => 'storeRequiredDetails', 'class' => 'form-horizontal'])}}
		{{ Form::close() }}
	@endforeach
@stop