@extends("layouts.master")

@section("content")
	@if ( ! empty( $success ) )
		<div class="alert alert-danger alert-block ">
			{{ $success }}
		</div>
	@endif
	<h1>This is where hte dashboard goes</h1>
@stop