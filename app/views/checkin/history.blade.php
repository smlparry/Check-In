@extends('layouts.master')

@section('content')
	<h1>User Check In History</h1>
	<ul>
		@foreach ( $history as $historyItem )
			<li>
				Checked in at: {{ $historyItem['parent_details_data']->name }}
				<br>
				At: {{ $historyItem['user_checked_in_data']->created_at }}
			</li>
		@endforeach

	</ul>
@stop