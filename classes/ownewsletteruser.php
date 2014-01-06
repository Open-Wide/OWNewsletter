<?php

class OWNewsletterUser extends eZPersistentObject {

	/**
	 *
	 * @var int if newsletter user has this status he wants do get newsletter but did not confirm his email
	 */
	const STATUS_PENDING = 0;

	/**
	 *
	 * @var int if newsletter user has this status he can get newsletter mails
	 */
	const STATUS_CONFIRMED = 1;
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
				'salutation' => array(
					'name' => 'Salutation',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'first_name' => array(
					'name' => 'FirstName',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'last_name' => array(
					'name' => 'LastName',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'organisation' => array(
					'name' => 'Organisation',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'birthday' => array(
					'name' => 'Birthday',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'data_xml' => array(
					'name' => 'AdditionalData',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'hash' => array(
					'name' => 'Hash',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'status' => array(
					'name' => 'Status',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'ez_user_id' => array(
					'name' => 'EzUserId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'creator_contentobject_id' => array(
					'name' => 'CreatorContentobjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'created' => array(
					'name' => 'Created',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'modifier_contentobject_id' => array(
					'name' => 'ModifierContentobjectId',
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
				'removed' => array(
					'name' => 'Removed',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'bounced' => array(
					'name' => 'Bounced',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'blacklisted' => array(
					'name' => 'Blacklisted',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'note' => array(
					'name' => 'Note',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'remote_id' => array(
					'name' => 'RemoteId',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'import_id' => array(
					'name' => 'ImportId',
					'datatype' => 'integer',
					'default' => '',
					'required' => false ),
				'bounce_count' => array(
					'name' => 'BounceCount',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'data_text' => array(
					'name' => 'DateText',
					'datatype' => 'string',
					'default' => '',
					'required' => false )
			),
			'keys' => array( 'id' ),
			'increment_key' => 'id',
			'function_attributes' => array(
				'name' => 'getName',
				'salutation_name' => 'getSalutationName',
				'is_confirmed' => 'isConfirmed',
				'is_removed_self' => 'isRemovedSelf',
				'is_removed' => 'isRemoved',
				'is_on_blacklist' => 'isOnBlacklist',
				'subscription_array' => 'getSubscriptionArray',
				'email_name' => 'getEmailName',
				'creator' => 'getCreatorUserObject',
				'modifier' => 'getModifierUserObject',
				'ez_user' => 'getEzUserObject',
				'status_name' => 'getStatusString',
				'status_identifier' => 'getStatusIdentifier',
				'active_subscriptions' => 'getActiveSubscriptions',
				'approved_subscriptions' => 'getApprovedSubscriptions',
				'approved_miling_lists' => 'getApprovedMailingLists',
			),
			'class_name' => 'OWNewsletterUser',
			'name' => 'ownl_user' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * Get Name of NL User
	 * use a tpl to have full flexebiltiy to render the name
	 *
	 * @return string
	 */
	function getName() {
		$newsletterIni = eZINI::instance( 'newsletter.ini' );
		$useTplForNameGeneration = $newsletterIni->variable( 'NewsletterUserSettings', 'UseTplForNameGeneration' );
		if ( $useTplForNameGeneration === 'enabled' ) {
			$tpl = eZTemplate::factory();
			$tpl->setVariable( 'nl_user', $this );
			$templateFile = 'design:newsletter/user/name.tpl';
			$name = strip_tags( trim( $tpl->fetch( $templateFile ) ) );
			unset( $tpl );
			return $name;
		} else {
			$name = trim( $this->attribute( 'salutation_name' ) . ' ' . $this->attribute( 'first_name' ) . ' ' . $this->attribute( 'last_name' ) );
			return $name;
		}
	}

	/**
	 * Get i18n for salutation id
	 * user newsletter.ini
	 * [NewsletterUserSettings]
	 * SalutationMappingArray[value_1]=Mr.
	 * SalutationMappingArray[value_2]=Mrs.
	 *
	 * so we can extent this
	 * @return string
	 */
	function getSalutationName() {
		$availableSalutationNameArray = self::getAvailablesSalutationsFromIni();
		$salutationId = (int) $this->attribute( 'salutation' );
		if ( array_key_exists( $salutationId, $availableSalutationNameArray ) ) {
			return $availableSalutationNameArray[$salutationId];
		} else {
			return '';
		}
	}

	/**
	 * Check if current object has status confirmed
	 *
	 * @return boolean
	 */
	function isConfirmed() {
		$status = $this->attribute( 'status' );
		return $status == self::STATUS_CONFIRMED ? true : false;
	}

	/**
	 * Check if current object has status self removed
	 *
	 * @return boolean
	 */
	function isRemovedSelf() {
		$status = $this->attribute( 'status' );
		return $status == self::STATUS_REMOVED_SELF ? true : false;
	}

	/**
	 * Check if current object has status removed
	 *
	 * @return boolean
	 */
	function isRemoved() {
		$status = $this->attribute( 'status' );
		return $status == self::STATUS_REMOVED_SELF || $status == self::STATUS_REMOVED_ADMIN ? true : false;
	}

	/**
	 * Check if current user object is on blacklist
	 * and if status is blacklisted
	 *
	 * @return boolean
	 */
	function isOnBlacklist() {
		$status = $this->attribute( 'status' );
		$isOnBlacklist = OWNewsletterBlacklistItem::isEmailOnBlacklist( $this->attribute( 'email' ) );
		if ( $isOnBlacklist ) {
			// fix up status blacklisted if it is not set
			if ( $status != CjwNewsletterUser::STATUS_BLACKLISTED ) {
				$this->setBlacklisted();
				return true;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Returns all subcriptions for the current user, which hasn't a REMOVE status
	 *
	 * @return array
	 */
	function getSubscriptionArray() {
		$listSubscriptionArray = array();
		$subscriptionArray = OWNewsletterSubscription::fetchListByNewsletterUserId( $this->attribute( 'id' ) );
		foreach ( $subscriptionArray as $subscriptionObject ) {
			$listSubscriptionArray[$subscriptionObject->attribute( 'mailing_list_contentobject_id' )] = $subscriptionObject;
		}
		return $listSubscriptionArray;
	}

	/**
	 * Return the name which will display in email  e.g. Max Mustermman
	 *
	 * @return string
	 */
	function getEmailName() {
		$emailName = '';
		$firstName = $this->attribute( 'first_name' );
		$lastName = $this->attribute( 'last_name' );

		if ( $firstName != '' ) {
			$emailName .= $firstName . ' ';
		}

		if ( $lastName != '' ) {
			$emailName .= $lastName;
		}

		return $emailName;
	}

	/**
	 * Get Creator user object
	 *
	 * @return unknown_type
	 */
	function getCreatorUserObject() {
		if ( $this->attribute( 'creator_contentobject_id' ) != 0 ) {
			$user = eZContentObject::fetch( $this->attribute( 'creator_contentobject_id' ) );
			return $user;
		} else {
			return false;
		}
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
	 * Get user object
	 *
	 * @return eZUser object
	 */
	function getEzUserObject() {
		$retVal = false;
		if ( $this->attribute( 'ez_user_id' ) != 0 ) {
			$retVal = eZUser::fetch( $this->attribute( 'ez_user_id' ) );
		}
		return $retVal;
	}

	/**
	 * get a translated string for the status code
	 * @return unknown_type
	 */
	function getStatusString() {
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

	/**
	 * Return all active subscriptions of the user
	 * 
	 * @return array of OWNewsletterSubscription
	 */
	function getActiveSubscriptions() {
		$conds = array(
			'newsletter_user_id' => $this->attribute( 'id' )
		);
		return OWNewsletterSubscription::fetchActiveList( $conds );
	}

	/**
	 * Return all approved subscriptions of the user
	 * 
	 * @return array of OWNewsletterSubscription
	 */
	function getApprovedSubscriptions() {
		$conds = array(
			'status' => OWNewsletterSubscription::STATUS_APPROVED,
			'newsletter_user_id' => $this->attribute( 'id' )
		);
		return OWNewsletterSubscription::fetchList( $conds );
	}

	/**
	 * Return all approved mailing lists of the user
	 * 
	 * @return array of OWNewsletterMailingList
	 */
	function getApprovedMailingLists() {
		$return = array();
		$approvedSubscriptions = $this->attribute( 'approved_subscriptions' );
		foreach ( $approvedSubscriptions as $approvedSubscription ) {
			$return[] = $approvedSubscription->attribute( 'mailing_list' );
		}
		return array_unique( $return );
	}

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Returns object by id
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
	 * Returns object by email
	 *
	 * @param string $email
	 * @return array / boolean
	 */
	static function fetchByEmail( $email ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array( 'email' => $email ), true );
		return $object;
	}

	/**
	 * Returns object by eZ User Id
	 *
	 * @param int $ezUserId
	 * @return NewsletterUser / boolean
	 */
	static function fetchByEzUserId( $ezUserId ) {
		if ( $ezUserId > 0 ) {
			$object = eZPersistentObject::fetchObject( self::definition(), null, array( 'ez_user_id' => $ezUserId ), true );
			return $object;
		}
		return false;
	}

	/**
	 * Returns object by hash
	 *
	 * @param string $hash
	 * @return object
	 */
	static function fetchByHash( $hash ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array( 'hash' => $hash ), true );
		return $object;
	}

	/**
	 * Fetch user by custom parameters and subscription custom parameters
	 *
	 * @param array $conds
	 * @param integer $limit
	 * @param integer $offset
	 * @param boolean $asObject
	 * @return array
	 */
	static function fetchListWithSubsricption( $conds, $limit = false, $offset = false, $asObject = true ) {
		$sortArr = array( 'last_name' => 'asc', 'first_name' => 'asc', );
		$limitArr = null;

		if ( (int) $limit != 0 ) {
			$limitArr = array(
				'limit' => $limit,
				'offset' => $offset );
		}
		$def = self::definition();
		$custom_fields = array_keys( $def['fields'] );
		foreach ( $custom_fields as $index => $field ) {
			$custom_fields[$index] = "ownl_user.$field as $field";
		}
		$custom_tables = null;
		$custom_conds = null;
		if ( isset( $conds['subscription'] ) ) {
			$custom_tables = array( 'ownl_subscription' );
			$custom_conds = ' AND ownl_user.id = ownl_subscription.newsletter_user_id';
			foreach ( $conds['subscription'] as $field => $value ) {
				$conds["ownl_subscription.$field"] = $value;
			}
			unset( $conds['subscription'] );
		}
		$objectList = eZPersistentObject::fetchObjectList( self::definition(), array(), $conds, $sortArr, $limitArr, $asObject, null, $custom_fields, $custom_tables, $custom_conds );
		return $objectList;
	}

	/**
	 * Count user by custom parameters and subscription custom parameters
	 *
	 * @param array $conds
	 * @param integer $limit
	 * @param integer $offset
	 * @param boolean $asObject
	 * @return array
	 */
	static function countListWithSubsricption( $conds ) {
		$custom_tables = null;
		$custom_conds = null;
		if ( isset( $conds['subscription'] ) ) {
			$custom_tables = array( 'ownl_subscription' );
			$custom_conds = ' AND ownl_user.id = ownl_subscription.newsletter_user_id';
			foreach ( $conds['subscription'] as $field => $value ) {
				$conds["ownl_subscription.$field"] = $value;
			}
			unset( $conds['subscription'] );
		}
		$field = '*';
		$customFields = array( array( 'operation' => 'COUNT( ' . $field . ' )', 'name' => 'row_count' ) );
		$rows = eZPersistentObject::fetchObjectList( self::definition(), array(), $conds, array(), null, false, false, $customFields, $custom_tables, $custom_conds );
		return $rows[0]['row_count'];
	}

	/*	 * **********************
	 * OBJECT METHODS
	 * ********************** */

	/**
	 * Update subscription list
	 * 
	 * @param array $newSubscriptionList
	 */
	public function updateSubscriptionList( $newSubscriptionList, $context = 'default' ) {
		$currentSubscriptionList = $this->attribute( 'subscription_array' );
		foreach ( $newSubscriptionList as $newSubscription ) {
			if ( $newSubscription['status'] > -1 ) {
				$newSubscription['newsletter_user_id'] = $this->attribute( 'id' );
				try {
					OWNewsletterSubscription::createOrUpdate( $newSubscription, $context );
				} catch ( Exception $e ) {
					$error = 'Failed to create or update subscription';
				}
			}
			unset( $currentSubscriptionList[$newSubscription['mailing_list_contentobject_id']] );
		}
		foreach ( $currentSubscriptionList as $currentSubscription ) {
			$currentSubscription->remove();
		}
		if ( isset( $error ) ) {
			throw new InvalidArgumentException( $error );
		}
	}

	/**
	 * set modified to current timestamp and set current User Id
	 * if first version use created as modified timestamp
	 */
	public function setModified() {
		if ( $this->attribute( 'id' ) > 1 ) {
			$this->setAttribute( 'modified', time() );
			$this->setAttribute( 'modifier_contentobject_id', eZUser::currentUserID() );
		} else {
			$this->setAttribute( 'modified', $this->attribute( 'created' ) );
			$this->setAttribute( 'modifier_contentobject_id', eZUser::currentUserID() );
		}
	}

	/**
	 * set current object blacklisted
	 * @return void
	 */
	public function setBlacklisted() {
		$this->setAttribute( 'status', self::STATUS_BLACKLISTED );
		// set all subscriptions and all open senditems to blacklisted
		$this->setAllNewsletterUserRelatedItemsToStatus( OWNewsletterSubscription::STATUS_BLACKLISTED );
		$this->store();
	}

	/**
	 * Set current object non-blacklisted
	 * User and subscriptions will be set to confirmed
	 * @return void
	 */
	public function setNonBlacklisted() {
		OWNewsletterLog::writeDebug(
				'OWNewsletterUser::setNonBlacklisted', 'user', 'blacklist', array( 'nl_user' => $this->attribute( 'id' ) )
		);

		// we determine the actual status by checking the various timestamps
		if ( $this->attribute( 'confirmed' ) != 0 ) {
			if ( $this->attribute( 'bounced' ) != 0 || $this->attribute( 'removed' ) != 0 ) {
				if ( $this->attribute( 'removed' ) > $this->attribute( 'bounced' ) ) {
					$this->setRemoved();
				} else {
					$this->setBounced();
				}
			}
			// confirmed, and not deleted nor bounced
			else {
				$this->setAttribute( 'status', self::STATUS_CONFIRMED );
			}
		}
		// not confirmed
		else {
			// might have been removed by admin
			if ( $this->attribute( 'removed' ) != 0 ) {
				$this->setRemoved( true );
			} else {
				$this->setAttribute( 'status', self::STATUS_PENDING );
			}
		}
		$this->setAttribute( 'blacklisted', 0 );

		// set all subscriptions and all open senditems to blacklisted
		foreach ( OWNewsletterSubscription::fetchListByNewsletterUserId( $this->attribute( 'id' ) ) as $subscription ) {
			$subscription->setNonBlacklisted();
		}

		$this->store();
	}

	/**
	 * set Modifed data if somebody store content
	 * (non-PHPdoc)
	 * @see kernel/classes/eZPersistentObject#store($fieldFilters)
	 */
	public function store( $fieldFilters = null ) {
		$this->setModified();
// find and set ez_user_id
		$this->findAndSetRelatedEzUserId();
		parent::store( $fieldFilters );
	}

	/**
	 * search the ez_user_id for the current nl email
	 * @return int $ezUserId / false
	 */
	public function findAndSetRelatedEzUserId() {
		$currentEzUserId = $this->attribute( 'ez_user_id' );
// if not set
		if ( $currentEzUserId == 0 ) {
			$email = $this->attribute( 'email' );
			if ( $email != '' ) {
				$existingEzUser = eZUser::fetchByEmail( $email );
				if ( is_object( $existingEzUser ) ) {
					$ezUserId = $existingEzUser->attribute( 'contentobject_id' );
					$this->setAttribute( 'ez_user_id', $ezUserId );
					return $ezUserId;
				}
			}
		} else {
			return $currentEzUserId;
		}

		return false;
	}

	/**
	 *
	 * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
	 */
	function setAttribute( $attr, $value ) {
// TODO check if modified should be update every time a attribute is set
// may be in store method better place to do this
		switch ( $attr ) {
			case 'status': {
					$currentTimeStamp = time();
					switch ( $value ) {
						case self::STATUS_CONFIRMED : {
								$this->setAttribute( 'confirmed', $currentTimeStamp );
								// if a user is confirmed reset bounce count
								$this->resetBounceCount();
							} break;

						case self::STATUS_BOUNCED_SOFT :
						case self::STATUS_BOUNCED_HARD : {
								$this->setAttribute( 'bounced', $currentTimeStamp );
								// set all subscriptions and all open senditems to bounced
								// see
								// setBounced
								// setAllNewsletterUserRelatedItemsToStatus
							} break;
						case self::STATUS_REMOVED_ADMIN :
						case self::STATUS_REMOVED_SELF : {
								$this->setAttribute( 'removed', $currentTimeStamp );
								// set all subscriptions and all open senditems to removed
							//
                    } break;
						case self::STATUS_BLACKLISTED : {
								$this->setAttribute( 'blacklisted', $currentTimeStamp );
								// set all subscriptions and all open senditems to blacklisted
								// see
								// setBlacklisted
								// setAllNewsletterUserRelatedItemsToStatus
							} break;
					}

					$statusOld = $this->attribute( 'status' );
					$statusNew = $value;

					if ( $statusOld != $statusNew ) {
						OWNewsletterLog::writeNotice(
								'set OWNewsletterUser::setAttribute', 'user', 'status', array(
							'nl_user' => $this->attribute( 'id' ),
							'status_old' => $statusOld,
							'status_new' => $statusNew,
							'modifier' => eZUser::currentUserID() )
						);
					} else {
						OWNewsletterLog::writeDebug(
								'set OWNewsletterUser::setAttribute', 'user', 'status', array(
							'nl_user' => $this->attribute( 'id' ),
							'status_old' => $statusOld,
							'status_new' => $statusNew,
							'modifier' => eZUser::currentUserID() )
						);
					}
					return eZPersistentObject::setAttribute( $attr, $value );
				} break;
			default:
				return eZPersistentObject::setAttribute( $attr, $value );
		}
	}

	/**
	 * set bounce_count to 0
	 */
	public function resetBounceCount() {
		$this->setAttribute( 'bounce_count', 0 );
	}

	/**
	 * call this function if a bounce mail for current user is detected
	 * if it is a hard bounce set
	 * @param boolean $isHardBounce
	 * @return unknown_type
	 */
	public function setRemoved( $byAdmin = false ) {
		if ( $byAdmin === true ) {
			$this->setAttribute( 'status', self::STATUS_REMOVED_ADMIN );
			$this->setAllNewsletterUserRelatedItemsToStatus( self::STATUS_REMOVED_ADMIN );
		} else {
			$this->setAttribute( 'status', self::STATUS_REMOVED_SELF );
			$this->setAllNewsletterUserRelatedItemsToStatus( self::STATUS_REMOVED_SELF );
		}
	}

	/**
	 * Mark user as confirmed
	 */
	public function confirm() {
		$this->setAttribute( 'status', self::STATUS_CONFIRMED );
		$this->sync();
		$this->store();
	}

	/**
	 * Unsubscribe from all approved subscription
	 */
	public function subscribeTo( $mailingListContentObjectID, $status = self::STATUS_PENDING, $context = 'default' ) {
		$newSubscription = array(
			'newsletter_user_id' => $this->attribute( 'id' ),
			'mailing_list_contentobject_id' => $mailingListContentObjectID,
			'status' => $status
		);
		$subscription = OWNewsletterSubscription::createOrUpdate( $newSubscription, $context );
		$subscription->setAttribute( 'status', $status );
		$subscription->store();
	}

	/**
	 * Unsubscribe from all approved subscription
	 */
	public function unsubscribe() {
		foreach ( $this->attribute( 'active_subscriptions' ) as $subscription ) {
			$subscription->unsubscribe();
		}
	}

	/**
	 * remove the current newlsetter user and all depending nl subscriptions
	 * @see kernel/classes/eZPersistentObject#remove($conditions, $extraConditions)
	 */
	function remove( $conditions = null, $extraConditions = null ) {
// remove subscriptions
		$currentNewsletterSubscriptionObjects = $this->attribute( 'subscription_array' );

		OWNewsletterLog::writeNotice( 'OWNewsletterUser::remove', 'user', 'remove', array(
			'nl_user' => $this->attribute( 'id' ),
			'subscription_count' => count( $currentNewsletterSubscriptionObjects ),
			'subscriptions_to_remove' => implode( '|', array_keys( $currentNewsletterSubscriptionObjects ) ),
			'modifier' => eZUser::currentUserID() )
		);

		foreach ( $currentNewsletterSubscriptionObjects as $subscription ) {
			$subscription->remove();
		}
		$blackListItem = OWNewsletterBlacklistItem::fetchByEmail( $this->attribute( 'email' ) );
		if ( is_object( $blackListItem ) ) {
			$blackListItem->setAttribute( 'newsletter_user_id', 0 );
			$blackListItem->store();
		}
		parent::remove( $conditions, $extraConditions );
	}
	
	public function sendSubscriptionConfirmationMail() {
		//TODO
	}

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	/**
	 * Create new OWNewsletterUser object
	 *
	 * @param array $dataArray
	 * @param int $status
	 * @return object
	 */
	static function createOrUpdate( $dataArray, $context = 'default' ) {
		self::validateNewsletterUserData( $dataArray );
		$email = $dataArray['email'];
		$row = array_merge( array(
			'created' => time(),
			'creator_contentobject_id' => eZUser::currentUserID(),
			'hash' => OWNewsletterUtils::generateUniqueMd5Hash( $email ),
			'remote_id' => 'ownl:' . $context . ':' . OWNewsletterUtils::generateUniqueMd5Hash( $email ),
			'status' => self::STATUS_PENDING ), $dataArray );
		$object = new self( $row );
		$object->setAttribute( 'status', $row['status'] );
		if ( $object->attribute( 'status' ) == self::STATUS_PENDING && $object->attribute( 'ez_user' ) !== FALSE ) {
			// auto confirm newsletter user related with a eZ user
			$object->setAttribute( 'status', self::STATUS_CONFIRMED );
		}
		$object->store();
		return $object;
	}

	/**
	 * Check if the data passed to create or update a newsletter user are correct
	 * 
	 * @param array $dataArray
	 * @throw InvalidArgumentException
	 */
	public static function validateNewsletterUserData( $dataArray ) {
		if ( !isset( $dataArray['email'] ) || empty( $dataArray['email'] ) ) {
			throw new InvalidArgumentException( 'User email is missing' );
		}
		$email = $dataArray['email'];
		$emailUser = self::fetchByEmail( $email );

		if ( !$emailUser instanceof self ) {
// no user have this email => data are OK
			return true;
		} else {
			if ( isset( $dataArray['id'] ) ) {
				if ( $dataArray['id'] == $emailUser->attribute( 'id' ) ) {
					// edit the user with the same mail and the same id
					return true;
				}
			}
			throw new InvalidArgumentException( 'A user with this email already exists' );
		}
	}

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */

	/**
	 *
	 * @return array[salutation_id]=>i18n
	 */
	static function getAvailablesSalutationsFromIni() {
		$newsletterIni = eZINI::instance( 'newsletter.ini' );
		$salutationMappingArray = $newsletterIni->variable( 'NewsletterUserSettings', 'SalutationMappingArray' );
		$salutationNameArray = array();
		foreach ( $salutationMappingArray as $salutationKey => $languageString ) {
			$salutationKeyExplode = explode( '_', $salutationKey );
			if ( isSet( $salutationKeyExplode[1] ) ) {
				$salutationId = (int) $salutationKeyExplode[1];
				$salutationNameArray[$salutationId] = ezpI18n::tr( 'newsletter/user/salutation', $languageString );
			}
		}
		return $salutationNameArray;
	}

	/**
	 * get an array of all available subscription status id with translated Names
	 * @return array
	 */
	static function getAvailableStatus( $arrayInfo = 'name' ) {
		if ( $arrayInfo == 'name' ) {
			return array(
				self::STATUS_PENDING => ezpI18n::tr( 'newsletter/user/status', 'Pending' ),
				self::STATUS_CONFIRMED => ezpI18n::tr( 'newsletter/user/status', 'Confirmed' ),
				self::STATUS_REMOVED_SELF => ezpI18n::tr( 'newsletter/user/status', 'Removed by user' ),
				self::STATUS_REMOVED_ADMIN => ezpI18n::tr( 'newsletter/user/status', 'Removed by admin' ),
				self::STATUS_BOUNCED_SOFT => ezpI18n::tr( 'newsletter/user/status', 'Bounced soft' ),
				self::STATUS_BOUNCED_HARD => ezpI18n::tr( 'newsletter/user/status', 'Bounced hard' ),
				self::STATUS_BLACKLISTED => ezpI18n::tr( 'newsletter/user/status', 'Blacklisted' )
			);
		} else {
			return array(
				self::STATUS_PENDING => 'pending',
				self::STATUS_CONFIRMED => 'confirmed',
				self::STATUS_REMOVED_SELF => 'removed_by_user',
				self::STATUS_REMOVED_ADMIN => 'removed_by_admin',
				self::STATUS_BOUNCED_SOFT => 'bounced_soft',
				self::STATUS_BOUNCED_HARD => 'bounced_hard',
				self::STATUS_BLACKLISTED => 'blacklisted',
			);
		}
	}

	/**
	 * This should be called if a user is bounced or blacklisted
	 * all related subscriptions and active senditems will be updated
	 *
	 * @param $status
	 * @return unknown_type
	 */
	private function setAllNewsletterUserRelatedItemsToStatus( $status ) {
		$newsletterUserId = $this->attribute( 'id' );

		switch ( $status ) {
			case OWNewsletterSubscription::STATUS_BOUNCED_SOFT:
			case OWNewsletterSubscription::STATUS_BOUNCED_HARD:
			case OWNewsletterSubscription::STATUS_BLACKLISTED:

				// update active subscriptions
				$activeSubscriptionList = OWNewsletterSubscription::fetchActiveList( array( 'newsletter_user_id' => $newsletterUserId ) );
				foreach ( $activeSubscriptionList as $subscription ) {
					if ( $subscription->attribute( 'status' ) == $status ) {
						OWNewsletterLog::writeDebug(
								'skip - already set this status - OWNewsletterUser::setAllNewsletterUserRelatedItemsToStatus', 'subscription', 'status', array(
							'status' => $status,
							'subscription_id' => $subscription->attribute( 'id' ),
							'nl_user' => $newsletterUserId ) );
					} else {
						$subscription->setAttribute( 'status', $status );
						$subscription->store();
					}
				}
				break;
		}
	}

}
