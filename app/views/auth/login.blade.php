@extends("layouts.master")

@section("content")
	<div class="login-wrapper">
		<div class="login-header">
			<h3>Login</h3>
		</div>

		{{ Form::open( array('route' => 'login.store', 'class' => 'form-horizontal') ) }}

			<!-- Name -->
			<div class="control-group {{{ $errors->has('email') ? 'error' : '' }}}">

				<div class="controls">
					{{ Form::text( 'email', Input::old('email'), array('placeholder' => 'Email Address') ) }}

					@if ( $errors->has('email') )
						<div class="alert alert-danger alert-block ">
							{{ $errors->first('email') }}
						</div>
					@endif

				</div>
			</div>

			<!-- Password -->
			<div class="control-group {{{ $errors->has('password') ? 'error' : '' }}}">
				<div class="controls">
					{{ Form::password('password', array('placeholder' => 'Password')) }}

					@if ( $errors->has('password') )
						<div class="alert alert-danger alert-block">
							{{ $errors->first('password') }}
						</div>
					@endif 
					
				</div>
			</div>

			<!-- Login button -->
			<div class="control-group">
				<div class="controls">
					{{ Form::submit( 'Login', array('class' => 'btn btn-main btn-block') ) }}
				</div>
			</div>

		{{ Form::close() }}
	</div>
@stop