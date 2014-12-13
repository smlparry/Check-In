@extends('layouts.master')

@section('content')

	<h1>Check in history for logged in user</h1>
	<ul>
		@if ( ! empty( $history ) )
			@foreach ( $history as $historyItem )
				<li>
					{{ 'Checked in at: ' . $historyItem['parent_details_data']->name . ' at: ' . $historyItem['user_checked_in_data']->created_at  }}
				</li>
			@endforeach
		@else
			<li>You have not checked in yet.</li>
		@endif
	</ul>
@stop