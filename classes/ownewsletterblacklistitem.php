<?php

class OWNewsletterBlacklistItem extends eZPersistentObject {

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

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

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