@extends('layouts.master')

@section('content')
	<h1> So this happened</h1>
	<ul>
		<li>Checked into: {{ User::find($parentId)->email }}</li>
	</ul>
@stop