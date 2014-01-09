<?php

/**
 * Mailqueue
 */
class OWNewsletterSendingItem extends eZPersistentObject {

	const STATUS_NEW = 0;
	const STATUS_SEND = 1;
	const STATUS_ABORT = 9;

	/**
	 *
	 * @param array $row
	 * @return void
	 */
	function __construct( $row ) {
		$this->eZPersistentObject( $row );
	}

	/**
	 * @return void
	 */
	static function definition() {
		return array( 'fields' => array(
				'edition_contentobject_id' => array(
					'name' => 'EditionContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'newsletter_user_id' => array(
					'name' => 'NewsletterUserId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'subscription_ids_string' => array(
					'name' => 'SubscriptionIdsString',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'created' => array( 'name' => 'Created',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'processed' => array( 'name' => 'Processed',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'status' => array( 'name' => 'Status',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'hash' => array( 'name' => 'Hash',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'bounced' => array( 'name' => 'Bounced',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
			),
			'keys' => array( 'edition_contentobject_id', 'newsletter_user_id' ),
			'function_attributes' => array(
				'newsletter_user' => 'getNewsletterUser',
				'newsletter_sending' => 'getNewsletterSending',
				'status_identifier' => 'getStatusIdentifier',
				'status_name' => 'getStatusName' ),
			'class_name' => 'OWNewsletterSendingItem',
			'name' => 'ownl_sending_item' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * Returns a string for the status code
	 * @return string
	 */
	function getStatusIdentifier() {
		switch ( $this->attribute( 'status' ) ) {
			case self::STATUS_NEW:
				return 'new';
			case self::STATUS_SEND:
				return 'send';
			case self::STATUS_ABORT:
				return 'abort';
		}
		return '-';
	}

	/**
	 * Returns a translated string for the status code
	 * @return string
	 */
	function getStatusName() {
		switch ( $this->attribute( 'status' ) ) {
			case self::STATUS_NEW:
				return ezpI18n::tr( 'newsletter/editionsenditem/status', 'New' );
			case self::STATUS_SEND:
				return ezpI18n::tr( 'newsletter/editionsenditem/status', 'Send' );
			case self::STATUS_ABORT:
				return ezpI18n::tr( 'newsletter/editionsenditem/status', 'Abort' );
		}
		return '-';
	}

	/**
	 * Returns the newsletter sending associted with the object
	 * 
	 * @return OWNewsletterUser
	 */
	function getNewsletterSending() {
		return OWNewsletterSending::fetch( $this->attribute( 'edition_contentobject_id' ) );
	}

	/**
	 * Returns the newsletter user associted with the object
	 * 
	 * @return OWNewsletterUser
	 */
	function getNewsletterUser() {
		return OWNewsletterUser::fetch( $this->attribute( 'newsletter_user_id' ) );
	}

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Return object by id
	 *
	 * @param integer $edition_contentobject_id
	 * @param integer $newsletter_user_id
	 * @return object
	 */
	static function fetch( $edition_contentobject_id, $newsletter_user_id ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array(
					'edition_contentobject_id' => $edition_contentobject_id,
					'newsletter_user_id' => $newsletter_user_id ), true );
		return $object;
	}

	/**
	 * Return object by hash
	 *
	 * @param string $hash
	 * @return object
	 */
	static function fetchByHash( $hash ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array(
					'hash' => $hash ), true );
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
		$limitArr = null;
		if ( (int) $limit != 0 ) {
			$limitArr = array(
				'limit' => $limit,
				'offset' => $offset );
		}
		$objectList = eZPersistentObject::fetchObjectList( self::definition(), null, $conds, null, $limitArr, $asObject, null, null, null, null );
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

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
	 */
	function setAttribute( $attr, $value ) {
		switch ( $attr ) {
			case 'status': {
					switch ( $value ) {
						case OWNewsletterSendingItem::STATUS_NEW:
							$this->setAttribute( 'created', time() );
							break;

						case OWNewsletterSendingItem::STATUS_SEND:
							$this->setAttribute( 'processed', time() );
							break;

						case OWNewsletterSendingItem::STATUS_ABORT:
							$this->setAttribute( 'processed', time() );
							break;
					}
					return eZPersistentObject::setAttribute( $attr, $value );
				} break;
			default:
				return eZPersistentObject::setAttribute( $attr, $value );
		}
	}

	/**
	 * set bounced to current timestamp so we know that this item has a bouncemail detected
	 * by this system
	 *
	 * return integer (timestamp)
	 */
	public function setBounced() {
		$timestamp = time();
		$this->setAttribute( 'bounced', $timestamp );
		$this->store();
		return $timestamp;
	}

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	/**
	 * Create a OWNewsletterSendingItem object
	 * 
	 * @param OWNewsletterSending $newsletterSending
	 * @param OWNewsletterUser $newsletterUser
	 * @param interger $status
	 */
	static function create( OWNewsletterSending $newsletterSending, OWNewsletterUser $newsletterUser, $status = OWNewsletterSendingItem::STATUS_NEW ) {
		$approvedMailingLists = $newsletterUser->attribute( 'approved_mailing_lists' );
		$approvedMailingListIDs = array();
		foreach ( $approvedMailingLists as $approvedMailingList ) {
			$approvedMailingListIDs[] = $approvedMailingList->attribute( 'id' );
		}
		$row = array(
			'edition_contentobject_id' => (int) $newsletterSending->attribute( 'edition_contentobject_id' ),
			'newsletter_user_id' => (int) $newsletterUser->attribute( 'id' ),
			'subscription_ids_string' => OWNewsletterUtils::arrayToString( array_intersect( $newsletterSending->attribute( 'mailing_lists_ids' ), $approvedMailingListIDs ) ),
			'hash' => OWNewsletterUtils::generateUniqueMd5Hash( $newsletterSending->attribute( 'edition_contentobject_id' ) . '-' . $newsletterUser->attribute( 'id' ) )
		);

		$object = new self( $row );
		$object->setAttribute( 'status', $status );
		$object->store();

		return $object;
	}

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */
}
