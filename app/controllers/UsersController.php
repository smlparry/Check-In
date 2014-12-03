<?php

class UsersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make("auth.login");
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{	
		// Return the login form
		return View::make("auth.register");
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function register()
	{
		// Register the user and log them in
		// Pass onto class function
		$register = new User;
		$userData = $register->userDataToArray( Input::all() );

 	if ( $register->isValid( $userData ) === true ){

	 	// Register the user
	 	$register->registerUser( $userData );

	 	// Log the user in
	 	$register->logUserIn( $userData );

	 	return Redirect::to('dash')->with('success', 'You have successfully logged in!');

	}

	return $register->isValid( $userData );

 	// Else return with errors
		

	}


}
