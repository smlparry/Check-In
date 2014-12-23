@extends('layouts.master')

@section('content')
	<h1>This is where i echo out the response from the connection attempt</h1>

	@if ( $response === true )
		<p>You have successfully connected to {{ User::find( $admin )->email }}</p>
	@elseif ( count($response) != 0 )
		<p>The place you are trying to connect to requires more details about you</p>
		<p>They require:</p>
		<ul>
			@foreach($response as $requiredDetail)
				<li>{{ $requiredDetail }}</li>
				{{ Form::open( ['route' => 'addRequiredDetail', 'class' => 
				'form-horizontal'] ) }}
					{{ Form::text( $requiredDetail ) }}
				{{ Form::close()}}
			@endforeach 
		</ul>
	@else
		<p>An error occured please try again. {{ link_to('checkin/connect', 'You can try again here') }}</p>
	@endif

@stop