@extends('layouts.master')

@section('content')

	<h1>Admin feed of checked in users</h1>
	<ul>

		@if ( ! empty( $feed ) )
			@foreach ( $feed as $feedItem )
				<li>
					Feed details: <br>
					User: {{ $feedItem['user']->email }}<br>
					@foreach ( $feedItem['user_details'] as $userDetails )
						{{ $userDetails->name }}<br>
						{{ $userDetails->address }}
					@endforeach 
				</li>
			@endforeach
		@elseif ( $feed === false )
			<li>You need to be an admin to perform this action</li>
		@else
			<li>No one has checked in yet.</li>
		@endif
	</ul>
@stop