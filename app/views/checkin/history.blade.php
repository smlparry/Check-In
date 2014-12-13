@extends('layouts.master')

@section('content')

	<h1>Check in history for logged in user</h1>
	<ul>
		@if ( ! empty( $history ) )
			@foreach ( $history as $historyItem )
				<li>	
					@foreach( $historyItem['parent_details_data'] as $parentDetails )
						{{ 'Checked in at: ' . $parentDetails->name }}
						<br>
						{{ 'At: ' . $historyItem['user_checked_in_data']->created_at  }}
					@endforeach
				</li>
			@endforeach
		@else
			<li>You have not checked in yet.</li>
		@endif
	</ul>
@stop