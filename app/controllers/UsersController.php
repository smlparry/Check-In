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
		// Log the user in
		$login = new User;
		$userData = $login->userDataToArray( Input::all() );

		// Check if the user data is valid
		$rules = $login->rulesLogin();
		$isValid = $login->isValid( $userData, $rules );
		$logInResponse = $login->logUserIn( $userData );

		if ( $isValid === true && $logInResponse === true ){

			return Redirect::to('dash')->with('success', 'Logged In Succesfully');

		} 

		return Redirect::to('login')->with( 'errors', $logInResponse );


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
	public function destroy()
	{
		Auth::logout();

		return Redirect::to('/')->with('success', 'Successfully Logged Out');
	}

	public function register()
	{
		// Register the user and log them in
		// Pass onto class function
		$register = new User;
		$userData = $register->userDataToArray( Input::all() );
		$rules = $register->rulesRegister();
		$isValid = $register->isValid( $userData, $rules );

	 	if ( $isValid === true ){

		 	// Register the user
		 	$register->registerUser( $userData );

		 	// Send off confirmation email
		 	$register->confirmationEmail( $userData );

		 	// Log the user in
		 	$register->logUserIn( $userData );

		 	// Add extra rows to the database
		 	$register->defaultUserDetails( Auth::id() );
		 	$register->defaultRequiredDetails( Auth::id() );

		 	return Redirect::to('dash')->with( 'success', 'You have successfully logged in!' );

		}

	 	// Else return with errors
		return Redirect::to('register')->with( 'errors', $isValid );

	}


}
