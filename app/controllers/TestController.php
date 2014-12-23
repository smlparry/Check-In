<?php

class TestController extends \BaseController {

	/*
		Random Testing
	 */
	public function index()
	{ 
		$test = new UserDetail;
		return $test->explodeKeyValueStringToArray( 'color,blue|gym,here|' );
	}


}
