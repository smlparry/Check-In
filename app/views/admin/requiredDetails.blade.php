@extends('layouts.master')

@section('content')
	<h1>This is where the admin specifies what information they require from their connected users</h1>

	@if ( $requiredDetails !== false )
		{{ Form::open(['route' => 'storeRequiredDetails', 'class' => 'form-horizontal'])}}
			<!-- Required details -->
			{{ Form::label('Required Details:') }}
			@foreach ( $requiredDetails->required_details as $requiredDetail )
				<div class="input-group">
					{{ Form::label( $requiredDetail ) }}
					{{ Form::checkbox( 'require_' . $requiredDetail, '', true ) }}
				</div>
			@endforeach
			<div id="additional-custom-details"></div>

			<!-- Add extra custom details -->
			{{ Form::button( '+ add additional required details', [ 'id' => 'add-custom-detail', 'class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#add_custom_details'] )}}


			{{ Form::submit( 'Submit', ['class' => 'btn btn-success'] )}}
		{{ Form::close() }}

		@include('modals.addCustomDetailModal')

	@else
		<p> You must be an admin to perform this function </p>
	@endif
@stop

@section('js')
	<script>
		$('#add-custom-detail-button').on('click', function(){
			var customDetail = $('#custom-detail').val();
			if ( customDetail ) {
				// Add additional form groups
				var formElement = '<div class="input-group"><label for="' + customDetail + '">' + customDetail + '</label><input checked="checked" name="require_' + customDetail + '" type="checkbox"></div>';

				// Close the modal
				$('#add_custom_details').modal('hide');

				// Append the form element to the main form
				$( formElement ).appendTo('#additional-custom-details');

			}

		});
	</script>
@stop