<?php

class OWNewsletterMailbox extends eZPersistentObject {

	/**
	 * Store ezc...Transport object global
	 */
	var $TransportObject = null;

	/**
	 * constructor
	 *
	 * @param mixed $row
	 * @return void
	 */
	function __construct( $row = array() ) {
		parent::__construct( $row );
	}

	/**
	 * data fields...
	 *
	 * @return array
	 */
	static function definition() {
		return array( 'fields' => array(
				'id' => array(
					'name' => 'Id',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'email' => array(
					'name' => 'Email',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'server' => array(
					'name' => 'Server',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'port' => array(
					'name' => 'Port',
					'datatype' => 'integer',
					'default' => null,
					'required' => false ),
				'username' => array(
					'name' => 'Username',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'password' => array(
					'name' => 'Password',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'type' => array(
					'name' => 'Type',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'is_activated' => array(
					'name' => 'IsActivated',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'is_ssl' => array(
					'name' => 'IsSsl',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'delete_mails_from_server' => array(
					'name' => 'DeleteMailsFromServer',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'last_server_connect' => array(
					'name' => 'LastServerConnect',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ) ),
			'keys' => array( 'id' ),
			'increment_key' => 'id',
			'class_name' => 'OWNewsletterMailbox',
			'name' => 'ownl_mailbox' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Return object by id
	 *
	 * @param integer $id
	 * @return object or boolean
	 */
	static public function fetch( $id ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array( 'id' => $id ), true );
		return $object;
	}

	/**
	 * Search all objects with custom conditions
	 *
	 * @param array $conds
	 * @param integer $limit
	 * @param integer $offset
	 * @param boolean $asObject
	 * @return array
	 */
	static function fetchList( $conds = array(), $limit = false, $offset = false, $asObject = true ) {
		$sortArr = array(
			'id' => 'asc' );
		$limitArr = null;

		if ( (int) $limit != 0 ) {
			$limitArr = array(
				'limit' => $limit,
				'offset' => $offset );
		}
		$objectList = eZPersistentObject::fetchObjectList( self::definition(), null, $conds, $sortArr, $limitArr, $asObject, null, null, null, null );
		return $objectList;
	}

	/**
	 * Count all subsciptions with custom conditions
	 *
	 * @param array $conds
	 * @return interger
	 */
	static function countList( $conds = array() ) {
		$objectList = eZPersistentObject::count( self::definition(), $conds );
		return $objectList;
	}

	/*	 * **********************
	 * OBJECT METHODS
	 * ********************** */

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	static function createOrUpdate( $row ) {
		$object = new self( $row );
		$object->store();
		return $object;
	}

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */
}
