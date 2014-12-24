<?php namespace Checkin\Transformers;

abstract class Transformer {
	
	protected $userDetail;

	public function __construct( \UserDetail $userDetail )
	{
		$this->userDetail = $userDetail;
	}

	/**
	 * Map over the array to output a formatted array with the item contents
	 * @param  array  $item [ An array of the items that will be outputted ]
	 * @return [type]       [description]
	 */
	public function transformCollection( array $item )
	{
		return array_map( [$this, 'transform'], $item );
	}

	/**
	 * Call the transform function from the abstract class
	 * @param  [type] $item [description]
	 * @return [type]       [description]
	 */
	public abstract function transform( $item );

}