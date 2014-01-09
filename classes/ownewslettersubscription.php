<?php

class OWNewsletterSubscription extends eZPersistentObject {

	const STATUS_PENDING = 0;
	const STATUS_CONFIRMED = 1;
	const STATUS_APPROVED = 2;
	const STATUS_REMOVED_SELF = 3;
	const STATUS_REMOVED_ADMIN = 4;

	/**
	 * @var int if nl user was deactive by a soft bounce
	 */
	const STATUS_BOUNCED_SOFT = 6;

	/**
	 * @var int if nl user was deactive by a hard bounce
	 */
	const STATUS_BOUNCED_HARD = 7;

	/**
	 * @var int if newsletter user has this status he get no emails anymore
	 */
	const STATUS_BLACKLISTED = 8;

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
	 * @return void
	 */
	static function definition() {
		return array(
			'fields' => array(
				'newsletter_user_id' => array(
					'name' => 'NewsletterUserId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'mailing_list_contentobject_id' => array(
					'name' => 'ListContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'hash' => array(
					'name' => 'Hash',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'status' => array(
					'name' => 'Status',
					'datatype' => 'integer',
					'default' => self::STATUS_PENDING,
					'required' => true ),
				'creator_contentobject_id' => array(
					'name' => 'CreatorContentObjectId',
					'datatype' => 'interger',
					'default' => 0,
					'required' => true ),
				'created' => array(
					'name' => 'Created',
					'datatype' => 'interger',
					'default' => 0,
					'required' => true ),
				'modifier_contentobject_id' => array(
					'name' => 'ModifierContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'modified' => array(
					'name' => 'Modified',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'confirmed' => array(
					'name' => 'Confirmed',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'approved' => array(
					'name' => 'Approved',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'removed' => array(
					'name' => 'Removed',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'remote_id' => array(
					'name' => 'RemoteId',
					'datatype' => 'string',
					'default' => '',
					'required' => true )
			),
			'function_attributes' => array(
				'id' => 'getId',
				'newsletter_user' => 'getNewsletterUserObject',
				'mailing_list' => 'getMailingListObject',
				'is_removed' => 'isRemoved',
				'is_removed_self' => 'isRemovedSelf',
				'is_blacklisted' => 'isBlacklisted',
				'can_be_approved' => 'canBeApproved',
				'can_be_removed' => 'canBeRemoved',
				'creator' => 'getCreatorUserObject',
				'modifier' => 'getModifierUserObject',
				'status_name' => 'getStatusName',
				'status_identifier' => 'getStatusIdentifier',
			),
			'keys' => array( 'mailing_list_contentobject_id', 'newsletter_user_id' ),
			'sort' => array( 'created' => 'asc' ),
			'class_name' => 'OWNewsletterSubscription',
			'name' => 'ownl_subscription' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * Return ID of the subscription
	 * 
	 * @return string ID
	 */
	function getId() {
		return $this->attribute( 'newsletter_user_id' ) . '/' . $this->attribute( 'mailing_list_contentobject_id' );
	}

	/**
	 * Return user newsletterUserObject
	 *
	 * @return object
	 */
	function getNewsletterUserObject() {
		$userObject = OWNewsletterUser::fetch( $this->attribute( 'newsletter_user_id' ) );
		return $userObject;
	}

	/**
	 * Return user newsletterListObject
	 *
	 * @return object
	 */
	function getMailingListObject() {
		$object = eZContentObject::fetch( $this->attribute( 'mailing_list_contentobject_id' ) );
		return $object;
	}

	/**
	 * Check if current object has a removestatus
	 *
	 * @return boolean
	 */
	function isRemoved() {
		$subscriptionStatus = $this->attribute( 'status' );
		return ( $subscriptionStatus == self::STATUS_REMOVED_ADMIN || $subscriptionStatus == self::STATUS_REMOVED_SELF ) ? true : false;
	}

	/**
	 * Check if current object has a status remove self
	 *
	 * @return boolean
	 */
	function isRemovedSelf() {
		$subscriptionStatus = $this->attribute( 'status' );
		return $subscriptionStatus == self::STATUS_REMOVED_SELF ? true : false;
	}

	/**
	 * Check if current object has a status blacklisted
	 *
	 * @return boolean
	 */
	function isBlacklisted() {
		$subscriptionStatus = $this->attribute( 'status' );
		return $subscriptionStatus == self::STATUS_BLACKLISTED ? true : false;
	}

	/**
	 * Check if the subscription can be approved
	 * 
	 * @return booleean
	 */
	function canBeApproved() {
		return !in_array( $this->attribute( 'status' ), array( self::STATUS_APPROVED, self::STATUS_REMOVED_SELF,
					self::STATUS_BLACKLISTED ) );
	}

	/**
	 * Check if the subscription can be removed
	 * 
	 * @return booleean
	 */
	function canBeRemoved() {
		return !in_array( $this->attribute( 'status' ), array( self::STATUS_REMOVED_SELF,
					self::STATUS_REMOVED_ADMIN, self::STATUS_BLACKLISTED ) );
	}

	/**
	 * Get Creator user object
	 *
	 * @return unknown_type
	 */
	function getCreatorUserObject() {
		$user = eZContentObject::fetch( $this->attribute( 'creator_contentobject_id' ) );
		return $user;
	}

	/**
	 * Get user object
	 *
	 * @return unknown_type
	 */
	function getModifierUserObject() {
		$retVal = false;
		if ( $this->attribute( 'modifier_contentobject_id' ) != 0 ) {
			$retVal = eZContentObject::fetch( $this->attribute( 'modifier_contentobject_id' ) );
		}
		return $retVal;
	}

	/**
	 * get a translated string for the status code
	 * @return unknown_type
	 */
	function getStatusName() {
		$statusString = '-';

		$availableStatusArray = self::getAvailableStatus();
		$currentStatusId = $this->attribute( 'status' );

		if ( array_key_exists( $currentStatusId, $availableStatusArray ) ) {
			$statusString = $availableStatusArray[$currentStatusId];
		}
		return $statusString;
	}

	/**
	 * get a translated string for the status code
	 * @return unknown_type
	 */
	function getStatusIdentifier() {
		$statusIdentifier = '-';

		$availableStatusArray = self::getAvailableStatus( 'identifier' );
		$currentStatusId = $this->attribute( 'status' );

		if ( array_key_exists( $currentStatusId, $availableStatusArray ) ) {
			$statusIdentifier = $availableStatusArray[$currentStatusId];
		}
		return $statusIdentifier;
	}

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Return object by id
	 *
	 * @param integer $mailing_list_contentobject_id
	 * @param integer $newsletter_user_id
	 * @return object
	 */
	static function fetch( $newsletter_user_id, $mailing_list_contentobject_id ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array(
					'mailing_list_contentobject_id' => $mailing_list_contentobject_id,
					'newsletter_user_id' => $newsletter_user_id ), true );
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
			'created' => 'desc' );
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

	/**
	 * Fetch all subscription by user_id incl. removed subscriptions
	 *
	 * @param integer $newsletterUserId
	 * @param boolean $asObject
	 * @return array
	 */
	static function fetchListByNewsletterUserId( $newsletterUserId, $asObject = true ) {
		return self::fetchList( array( 'newsletter_user_id' => (int) $newsletterUserId ), false, false, $asObject );
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
	static function fetchActiveList( $conds = array(), $limit = false, $offset = false, $asObject = true ) {
		$sortArr = array(
			'created' => 'desc' );
		$limitArr = null;

		if ( (int) $limit != 0 ) {
			$limitArr = array(
				'limit' => $limit,
				'offset' => $offset );
		}
		$conds['status'] = array( array( self::STATUS_PENDING,
				self::STATUS_CONFIRMED,
				self::STATUS_APPROVED,
				self::STATUS_BOUNCED_SOFT,
				self::STATUS_BOUNCED_HARD ) );
		$objectList = eZPersistentObject::fetchObjectList( self::definition(), null, $conds, $sortArr, $limitArr, $asObject, null, null, null, null );
		return $objectList;
	}

	/*	 * **********************
	 * OBJECT METHODS
	 * ********************** */

	/**
	 * Unsubscribe subscription only if not blacklisted or if not removed self
	 *
	 * @return boolean
	 */
	public function unsubscribe() {
		if ( $this->attribute( 'status' ) == self::STATUS_BLACKLISTED || $this->attribute( 'status' ) == self::STATUS_REMOVED_SELF ) {
			return false;
		} else {
			$this->setAttribute( 'status', self::STATUS_REMOVED_SELF );
			$this->sync();
			$this->store();
			return true;
		}
	}

	/**
	 * approve subscription
	 *
	 * @return void
	 */
	public function approve() {
		$this->setAttribute( 'status', self::STATUS_APPROVED );
		$this->sync();
		$this->store();
	}

	/**
	 * rmove subscription
	 *
	 * @return void
	 */
	public function removeByAdmin() {
		$this->setAttribute( 'status', self::STATUS_REMOVED_ADMIN );
		$this->sync();
		$this->store();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
	 */
	function setAttribute( $attr, $val ) {
		switch ( $attr ) {
			case 'status':
				// only update timestamp and status if status id is changed
				if ( $this->attribute( 'status' ) == $val ) {
					return;
				}
				$currentTimeStamp = time();
				// set status timestamps
				switch ( $val ) {
					case self::STATUS_CONFIRMED : {
							$this->setAttribute( 'removed', 0 );
							$this->setAttribute( 'confirmed', $currentTimeStamp );
							$mailingList = $this->attribute( 'mailing_list' );

							// set approve automatically if defined in list config
							if ( is_object( $mailingList ) and (boolean) $mailingList->attribute( 'auto_approve_registered_user' ) == true ) {
								$this->setAttribute( 'approved', $currentTimeStamp );
								$val = self::STATUS_APPROVED;
							} else {
								// if subscription status is changed from approved to confirmed the approved timestamp should be removed
								$this->setAttribute( 'approved', 0 );
							}
						} break;

					case self::STATUS_APPROVED: {
							$this->setAttribute( 'approved', $currentTimeStamp );
							$this->setAttribute( 'removed', 0 );
						} break;

					case self::STATUS_REMOVED_ADMIN:
					case self::STATUS_REMOVED_SELF: {
							$this->setAttribute( 'removed', $currentTimeStamp );
						} break;
				}
				$this->setAttribute( 'modified', $currentTimeStamp );
				parent::setAttribute( $attr, $val );
				break;
			default:
				parent::setAttribute( $attr, $val );
				break;
		}
	}

	/**
	 * Reverts the blacklisted status by checking the various operation timestamps
	 */
	public function setNonBlacklisted() {
		$status = self::STATUS_PENDING;
		$lastActionDate = 0;
		if ( $this->attribute( 'approved' ) > $lastActionDate ) {
			$status = self::STATUS_APPROVED;
			$lastActionDate = $this->attribute( 'approved' );
		}
		if ( $this->attribute( 'confirmed' ) > $lastActionDate ) {
			$status = self::STATUS_CONFIRMED;
			$lastActionDate = $this->attribute( 'confirmed' );
		}
		if ( $this->attribute( 'removed' ) > $lastActionDate ) {
			$status = self::STATUS_REMOVED_ADMIN;
			$lastActionDate = $this->attribute( 'removed' );
		}
		$this->setAttribute( 'status', $status );
		$this->store();
	}

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	/**
	 * Create new OWNewsletterSubscription object
	 *
	 * @param integer $mailingListContentObjectId
	 * @param unknown_type $status
	 * @return object
	 */
	static function createOrUpdate( $dataArray, $context = 'default' ) {
		self::validateSubscriptionData( $dataArray );
		if ( !isset( $dataArray['status'] ) ) {
			$dataArray['status'] = self::STATUS_PENDING;
		}
		$newsletterUserId = $dataArray['newsletter_user_id'];
		$row = array_merge( array(
			'modified' => time(),
			'modifier_contentobject_id' => eZUser::currentUserID() ), $dataArray );
		$object = new OWNewsletterSubscription( $row );
		if ( $object->attribute( 'created' ) == 0 ) {
			$object->setAttribute( 'created', time() );
			$object->setAttribute( 'creator_contentobject_id', eZUser::currentUserID() );
			$object->setAttribute( 'hash', OWNewsletterUtils::generateUniqueMd5Hash( $newsletterUserId ) );
			$object->setAttribute( 'remote_id', 'ownl:' . $context . ':' . OWNewsletterUtils::generateUniqueMd5Hash( $newsletterUserId ) );
		}
		$object->setAttribute( 'status', $dataArray['status'] );
		$object->store();
		return $object;
	}

	/**
	 * Check if the data passed to create or update a subscription are correct
	 * 
	 * @param array $dataArray
	 * @throw InvalidArgumentException
	 */
	public static function validateSubscriptionData( $dataArray ) {
		if ( !isset( $dataArray['newsletter_user_id'] ) || empty( $dataArray['newsletter_user_id'] ) ) {
			throw new InvalidArgumentException( 'User ID is missing' );
		}
		if ( !isset( $dataArray['mailing_list_contentobject_id'] ) || empty( $dataArray['mailing_list_contentobject_id'] ) ) {
			throw new InvalidArgumentException( 'Mailing list ID is missing' );
		}
	}

	/**
	 * Remove subscription by user self
	 *
	 * @see newsletter/configure
	 *
	 * @param integer $mailingListContentObjectId
	 * @param integer $newsletterUserId
	 * @return object
	 */
	static function removeSubscriptionByNewsletterUserSelf( $mailingListContentObjectId, $newsletterUserId ) {
		$existingSubscriptionObject = self::fetch( $newsletterUserId, $mailingListContentObjectId );
		if ( is_object( $existingSubscriptionObject ) ) {
			$existingSubscriptionObject->unsubscribe();
		}
		return $existingSubscriptionObject;
	}

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */

	/**
	 * get an array of all available subscription status id with translated Names
	 * @return array
	 */
	static function getAvailableStatus( $arrayInfo = 'name' ) {
		if ( $arrayInfo == 'name' ) {
			return array(
				self::STATUS_PENDING => ezpI18n::tr( 'newsletter/subscription/status', 'Pending' ),
				self::STATUS_CONFIRMED => ezpI18n::tr( 'newsletter/subscription/status', 'Confirmed' ),
				self::STATUS_APPROVED => ezpI18n::tr( 'newsletter/subscription/status', 'Approved' ),
				self::STATUS_REMOVED_SELF => ezpI18n::tr( 'newsletter/subscription/status', 'Removed by user' ),
				self::STATUS_REMOVED_ADMIN => ezpI18n::tr( 'newsletter/subscription/status', 'Removed by admin' ),
				self::STATUS_BOUNCED_SOFT => ezpI18n::tr( 'newsletter/subscription/status', 'Bounced soft' ),
				self::STATUS_BOUNCED_HARD => ezpI18n::tr( 'newsletter/subscription/status', 'Bounced hard' ),
				self::STATUS_BLACKLISTED => ezpI18n::tr( 'newsletter/subscription/status', 'Blacklisted' )
			);
		} else {
			return array(
				self::STATUS_PENDING => 'pending',
				self::STATUS_CONFIRMED => 'confirmed',
				self::STATUS_APPROVED => 'approved',
				self::STATUS_REMOVED_SELF => 'removed_by_user',
				self::STATUS_REMOVED_ADMIN => 'removed_by_admin',
				self::STATUS_BOUNCED_SOFT => 'bounced_soft',
				self::STATUS_BOUNCED_HARD => 'bounced_hard',
				self::STATUS_BLACKLISTED => 'blacklisted',
			);
		}
	}

}
