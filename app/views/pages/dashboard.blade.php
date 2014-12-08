@extends("layouts.master")

@section("content")
	<?php $success = Session::get( 'success' ) ?>
	@if ( ! empty( $success ) )
		<div class="alert alert-success alert-block ">
			{{ $success }}
		</div>
	@endif

	<h1><a href="/">This is where the dashboard goes</a></h1>
@stop