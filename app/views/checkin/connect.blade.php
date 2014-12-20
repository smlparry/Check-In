@extends('layouts.master')

@section('content')
	<h1>This is where you show a listing of all the admin accounts and give the user the ability to connect to such place</h1>

	<ul>
		@foreach( $availableConnections as $adminUser )
			{{ Form::open( ['route' => 'addConnection', 'form-horizontal'] )}}
				{{ Form::hidden( 'admin_id', $adminUser->id ) }}
				{{ Form::submit('Connect to: ' . $adminUser->email , ['class' => 'btn btn-main' ]) }}
			{{ Form::close() }}
		@endforeach 
	</ul>
@stop