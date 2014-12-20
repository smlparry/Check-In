@extends('layouts.master')

@section('content')
	<h1>This is where you show a listing of all the admin accounts and give the user the ability to connect to such place</h1>

	<ul>
		@foreach($availableConnections as $adminUser)
			<li>{{ $adminUser->email }}</li>
		@endforeach 
	</ul>
@stop