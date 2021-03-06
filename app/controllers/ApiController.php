<?php

class ApiController extends \BaseController {

	protected $statusCode = 200;

	/**
	 * General getter for the status code
	 * @return [type] [description]
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * General setter for the status code
	 * @param [type] $statusCode [description]
	 */
	public function setStatusCode( $statusCode )
	{
		$this->statusCode = $statusCode;
		return $this;
	}

	/**
	 * General respond function that outputs the json from the data
	 * @param  [type] $data    [description]
	 * @param  [type] $headers [description]
	 * @return [type]          [description]
	 */
	public function respond( $data, $headers = [] )
	{
		return Response::json( $data, $this->statusCode, $headers );
	}

	/**
	 * Respond with an error message 
	 * @param  [type] $message [Optional message to show, should be set somewhere else before calling this function]
	 * @return [type]          [description]
	 */
	public function respondWithError( $message )
	{
		return $this->respond([
		            		'error' => [
		            			'message' => $message,
		            		],
		            		'status_code' => $this->getStatusCode()
		                ]);
	}

	public function respondWithSuccess( $message, $data )
	{
		return $this->respond([
	                      'message' => $message,
	                      'data' => $data,
	                      'status_code' => $this->getStatusCode()
			            ]);
	}

	/**
	 * Respond with the results
	 * @param  [type] $resultType [The name of the results that are being shown]
	 * @param  [type] $results    [The object or array of results]
	 * @return [type]             [description]
	 */
	public function respondWithResults( $resultType, $results )
	{
		return $this->respond([
		            		$resultType => $results,
		            		'status_code' => $this->getStatusCode()
		                ]);
	}

	/**
	 * Set the status code to 404 then respond with the error
	 * @param  string $message [Optional error message to display]
	 * @return [type]          [description]
	 */
	public function respondNoResults( $message = 'No results found.' )
	{
		return $this->setStatusCode(404)->respondWithError( $message );
	}	

	public function respondAccessDenied( $message = 'You do not have the correct permissions to perform this request.')
	{
		return $this->setStatusCode(403)->respondWithError( $message );
	}

	public function respondInvalidCheckin( $message = 'User does not have correct permissions to be checked into.')
	{
		return $this->setStatusCode(403)->respondWithError( $message );
	}

	public function respondInvalidRequest( $message = 'Additional arguments need to be supplied' )
	{
		return $this->setStatusCode(412)->respondWithError( $message );
	}

	public function respondSuccessfullCheckin( $message = 'Successfully Checked In', $adminObject )
	{
		return $this->setStatusCode(200)->respondWithSuccess( $message, $adminObject );
	}

	public function respondWithRequiredDetails( $requiredDetails )
	{
		return $this->setStatusCode(412)->respondWithResults( 'required_details', $requiredDetails );
	}

	public function respondNoUserDetailsFound( $message = 'No user details found for user attempting to connect')
	{
		return $this->setStatusCode(412)->respondWithError( $message );
	}
}