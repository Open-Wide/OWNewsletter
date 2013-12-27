<?php

class OWNewsletterBlacklistItem extends eZPersistentObject {

	/**
	 * Constructor
	 *
	 * @param array $row
	 * @return void
	 */
	function __construct( $row = array() ) {
		$this->eZPersistentObject( $row );
	}
	
	/**
	 * data fields...
	 *
	 * @return array
	 */
	static function definition() {
		return array( 'fields' => array(
				'id' => array( 'name' => 'Id',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'email_hash' => array( 'name' => 'EmailHash',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'email' => array( 'name' => 'Email',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'newsletter_user_id' => array( 'name' => 'NewsletterUserId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'created' => array( 'name' => 'Created',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'creator_contentobject_id' => array( 'name' => 'CreatorContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'note' => array( 'name' => 'Note',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
			),
			'keys' => array( 'id' ),
			'increment_key' => 'id',
			'function_attributes' => array(
				'newsletter_user_object' => 'getNewsletterUserObject',
				'creator' => 'getCreatorUserObject',
			),
			'class_name' => 'OWNewsletterBlacklistItem',
			'name' => 'ownl_blacklist_item' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * Fetches the newsletter object the blacklist object is attached to
	 *
	 * @return OWNewsletterUser
	 */
	function getNewsletterUserObject() {
		if ( $this->attribute( 'newsletter_user_id' ) != 0 ) {
			$user = OWNewsletterUser::fetch( $this->attribute( 'newsletter_user_id' ) );
			return $user;
		} else {
			return false;
		}
	}

	/**
	 * Get Creator user object
	 *
	 * @return eZContentObject
	 */
	function getCreatorUserObject() {
		if ( $this->attribute( 'creator_contentobject_id' ) != 0 ) {
			$user = eZContentObject::fetch( $this->attribute( 'creator_contentobject_id' ) );
			return $user;
		} else {
			return false;
		}
	}

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Return object by id
	 *
	 * @param integer $id
	 * @return object
	 */
	static function fetch( $id ) {
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
			'email' => 'asc' );
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
	 * Count all object with custom conditions
	 *
	 * @param array $conds
	 * @return interger
	 */
	static function countList( $conds = array() ) {
		$objectList = eZPersistentObject::count( self::definition(), $conds );
		return $objectList;
	}

	/**
	 * fetch OWNewsletterBlacklistItem object by email
	 * generae hash from email and look for existing hash
	 * => so it is possible to delete the email make the user anonym
	 * but we can ask the system if the email is on blacklist
	 * return false if not found
	 *
	 * @param string $email
	 * @param boolean $asObject
	 * @return OWNewsletterBlacklistItem
	 */
	public static function fetchByEmail( $email, $asObject = true ) {
		$condArray = array( 'email_hash' => self::generateEmailHash( $email ) );
		return eZPersistentObject::fetchObject( self::definition(), null, $condArray, $asObject );
	}

	/*	 * **********************
	 * OBJECT METHODS
	 * ********************** */

	/**
	 * if nl user exists update this data to
	 * (non-PHPdoc)
	 * @see kernel/classes/eZPersistentObject#store($fieldFilters)
	 */
	public function store( $fieldFilters = null ) {
		$newsletterUserObject = $this->getNewsletterUserObject();
		if ( is_object( $newsletterUserObject ) ) {
			$newsletterUserObject->setBlacklisted();
		}
		parent::store( $fieldFilters );
	}

	/**
	 * When a blacklist item is removed, remove the blacklist entries for the user
	 */
	public function remove( $conditions = null, $extraConditions = null ) {
		$newsletterUserObject = $this->getNewsletterUserObject();
		if ( is_object( $newsletterUserObject ) ) {
			$newsletterUserObject->setNonBlacklisted();
		}
		return parent::remove( $conditions = null, $extraConditions = null );
	}

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	static function addToBlacklist( $email ) {

		$newsletterUserObject = OWNewsletterUser::fetchByEmail( $email );
		$newsletterUserId = 0;
		if ( is_object( $newsletterUserObject ) ) {
			$newsletterUserId = $newsletterUserObject->attribute( 'id' );
		}
		$row = array( 'email' => strtolower( $email ),
			'created' => time(),
			'creator_contentobject_id' => eZUser::currentUserID(),
			'email_hash' => self::generateEmailHash( $email ),
			'newsletter_user_id' => $newsletterUserId
		);
		$object = new self( $row );
		$object->store();
		return $object;
	}

	static function removeFromBlacklist( $email ) {
		$blacklistItem = self::fetchByEmail( $email );
		if ( is_object( $blacklistItem ) ) {
			$blacklistItem->remove();
		}
	}

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */

	/**
	 * generate emailHash for mail
	 * @param string $email
	 * @return string emailHash
	 */
	public static function generateEmailHash( $email ) {
		$emailHash = md5( strtolower( trim( $email ) ) );
		return $emailHash;
	}

}

?>