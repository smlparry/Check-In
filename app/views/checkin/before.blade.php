@extends('layouts.master')

@section('content')
	
	{{ Form::open( array('route' => 'checkUserIn', 'class' => 'form-horizontal') ) }}
			<!-- Checkin -->
			<div class="control-group">

				<div class="controls">
					{{ Form::hidden('id', Auth::id() ) }}
					{{ Form::hidden('parent_id', $parentId ) }}
					{{ Form::submit('Click Me!') }}
				</div>
				
			</div>
	{{ Form::close() }}
@stop