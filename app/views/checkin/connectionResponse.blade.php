@extends('layouts.master')

@section('content')
	<h1>This is where i echo out the response from the connection attempt</h1>

	@if ( $response === true )

		<p>You have successfully connected to {{ User::find( $admin )->email }}</p>

	@elseif ( count($response) != 0 )

		<p>The place you are trying to connect to requires more details about you</p>
		<p>They require:</p>
		<ul>
			{{ Form::open( ['route' => 'addRequiredDetails', 'class' => 
				'form-horizontal'] ) }}

				@foreach($response as $requiredDetail)
					<li>{{ $requiredDetail }}</li>
						@if ( Session::has('errors') )
							@if ( in_array( $requiredDetail, $errors ))
								{{ 'You need to fill out this feild' }}
							@endif 
						@endif
						{{ Form::text( $requiredDetail, Input::old( $requiredDetail ) ) }}
				@endforeach
				{{ Form::hidden( 'admin_id', $admin ) }}
				{{ Form::submit( 'Add required details', ['class' => 'btn btn-success'] )}}
			{{ Form::close()}}
		</ul>
	@else
		<p>An error occured please try again. {{ link_to('checkin/connect', 'You can try again here') }}</p>
	@endif

@stop