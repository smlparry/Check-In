@extends('layouts.master')

@section('content')
	<h1>User Check In History</h1>
	<ul>
		@foreach ( $history as $historyItem )
			<li>
				{{ print_r($historyItem['parent_data']) }}
			</li>
		@endforeach

	</ul>
@stop