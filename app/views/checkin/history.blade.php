@extends('layouts.master')

@section('content')

	<h1>User Check In History</h1>
	<ul>
		@if ( ! empty($history) )
			@foreach ( $history as $historyItem )
				<li>
					{{ 'Checked in at: ' . $historyItem['parent_details_data']->name . ' at: ' . $historyItem['user_checked_in_data']->created_at  }}
				</li>
			@endforeach
		@else
			<li>User has not checked in yet.</li>
		@endif
	</ul>
@stop