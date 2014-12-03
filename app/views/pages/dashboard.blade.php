@extends("layouts.master")

@section("content")
	<?php $success = Session::get( 'success' ) ?>
	@if ( ! empty( $success ) )
		<div class="alert alert-success alert-block ">
			{{ $success }}
		</div>
	@endif

	<h1>This is where hte dashboard goes</h1>
@stop