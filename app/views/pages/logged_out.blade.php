@extends('layouts.master')

@section("content")
	<ul>
		<li>{{ link_to('login', 'Login') }}</li>
		<li>{{ link_to('dash', 'Dashboard') }}</li>
		<li></li>
		<li></li>
	</ul>
	<?php
	if(DB::connection()->getDatabaseName())
	{
	   echo "conncted sucessfully to database ".DB::connection()->getDatabaseName();
	}
	?>
@stop