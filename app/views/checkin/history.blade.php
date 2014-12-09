@extends('layouts.master')

@section('content')
	<h1>User Check In History</h1>
	<ul>
		@foreach ( $history as $historyItem )
			<li>
				{{ $historyItem['user_data']->created_at }}
			</li>
		@endforeach

	</ul>
@stop