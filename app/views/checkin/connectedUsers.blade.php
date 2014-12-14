@extends('layouts.master')

@section('content')
	
	<h1>Connected Users:</h1>
	<ul>

		@if ( ! empty( $connectedUsers ) )

			@foreach( $connectedUsers as $connectedUser )
			<li>

				Details: <br>
				{{ $connectedUser['user']->email }}<br>

				@foreach ($connectedUser['user_details'] as $userDetails )
					{{ $userDetails->name }}<br>
					{{ $userDetails->address }}
				@endforeach

			</li>
			@endforeach 
		@elseif ( $connectedUsers === false )
			<li> You need to be a registered place to have connected users </li>
		@else
			<li>You have no connected users</li>
		@endif
	</ul>

@stop