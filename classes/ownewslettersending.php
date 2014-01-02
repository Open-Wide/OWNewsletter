<?php

class OWNewsletterSending extends eZPersistentObject {

	const STATUS_DRAFT = 0;
	const STATUS_WAIT_FOR_PROCESS = 1;
	const STATUS_MAILQUEUE_CREATED = 2;
	const STATUS_MAILQUEUE_PROCESS_STARTED = 3;
	const STATUS_MAILQUEUE_PROCESS_FINISHED = 4;
	const STATUS_ABORT = 9;

	/**
	 * Contructor
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
				'edition_contentobject_id' => array( 'name' => 'EditionContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'edition_contentobject_version' => array( 'name' => 'EditionContentObjectVersion',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'newsletter_contentobject_id' => array( 'name' => 'NewsletterContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'newsletter_contentobject_version' => array( 'name' => 'NewsletterContentObjectVersion',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'mailing_lists_string' => array(
					'name' => 'MailingListsString',
					'datatype' => 'string',
					'default' => 'default',
					'required' => true ),
				'siteaccess' => array( 'name' => 'SiteAccess',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'creator_id' => array( 'name' => 'CreatorId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'created' => array( 'name' => 'Created',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'waiting_for_process' => array( 'name' => 'WaitingForProcess',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'mailqueue_created' => array( 'name' => 'MailQueueCreated',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'mailqueue_process_started' => array( 'name' => 'MailQueueProcessStarted',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'mailqueue_process_finished' => array( 'name' => 'MailQueueProcessFinished',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'mailqueue_process_aborted' => array( 'name' => 'MailQueueProcessAborted',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'status' => array( 'name' => 'Status',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'serialized_output' => array( 'name' => 'SerializedOutput',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'hash' => array( 'name' => 'Hash',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'sender_email' => array( 'name' => 'SenderEmail',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'sender_name' => array( 'name' => 'SenderName',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'personalize_content' => array( 'name' => 'PersonalizeContent',
					'datatype' => 'Integer',
					'default' => 0,
					'required' => false ),
			),
			'keys' => array( 'edition_contentobject_id' ),
			'function_attributes' => array(
				'newsletter_object' => 'getNewsletterObject',
				'edition_object' => 'getEditionObject',
				'can_abort' => 'canAbort',
				'send_items_statistic' => 'getSendItemsStatistic',
				'output' => 'getOutput',
			),
			'class_name' => 'OWNewsletterSending',
			'name' => 'ownl_sending' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	public function getNewsletterObject() {
		if ( $this->attribute( 'status' ) == self::STATUS_DRAFT ) {
			return OWNewsletter::fetchLastVersion( $this->attribute( 'newsletter_contentobject_id' ) );
		}
		return OWNewsletter::fetchByCustomConditions( array(
					'contentobject_id' => $this->attribute( 'newsletter_contentobject_id' ),
					'contentobject_attribute_version' => $this->attribute( 'newsletter_contentobject_version' )
				) );
	}

	public function getEditionObject() {
		if ( $this->attribute( 'status' ) == self::STATUS_DRAFT ) {
			return OWNewsletterEdition::fetchLastVersion( $this->attribute( 'edition_contentobject_id' ) );
		}
		return OWNewsletterEdition::fetchByCustomConditions( array(
					'contentobject_id' => $this->attribute( 'edition_contentobject_id' ),
					'contentobject_attribute_version' => $this->attribute( 'edition_contentobject_version' )
				) );
	}

	/**
	 * Check if sending can be abort
	 * 
	 * @return boolean
	 */
	public function canAbort() {
		return $this->attribute( 'status' ) != self::STATUS_MAILQUEUE_PROCESS_FINISHED || $this->attribute( 'status' ) != self::STATUS_ABORT;
	}

	/**
	 * Statistic data about sending process
	 *
	 * @return array
	 */
	public function getSendItemsStatistic() {
		return array(
			'items_count' => 0,
			'items_not_send' => 0,
			'items_send' => 0,
			'items_send_in_percent' => 0,
			'items_bounced' => 0 );
	}

	/**
	 * Return unserialized output
	 */
	public function getOutput() {
		return unserialize( $this->attribute( 'serialized_output' ) );
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
		$object = eZPersistentObject::fetchObject( self::definition(), null, array( 'edition_contentobject_id' => $id ), true );
		return $object;
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
					if ( $value === self::STATUS_WAIT_FOR_PROCESS ) {
						$this->setAttribute( 'waiting_for_process', time() );
					} elseif ( $value === self::STATUS_MAILQUEUE_CREATED ) {
						$this->setAttribute( 'mailqueue_created', time() );
					} elseif ( $value === self::STATUS_MAILQUEUE_PROCESS_STARTED ) {
						$this->setAttribute( 'mailqueue_process_started', time() );
					} elseif ( $value === self::STATUS_MAILQUEUE_PROCESS_FINISHED ) {
						$this->setAttribute( 'mailqueue_process_finished', time() );
					} elseif ( $value === self::STATUS_ABORT ) {
						$this->setAttribute( 'mailqueue_process_aborted', time() );
					}
					return eZPersistentObject::setAttribute( $attr, $value );
				} break;
			default:
				return eZPersistentObject::setAttribute( $attr, $value );
		}
	}

	/**
	 * reimplementation
	 * update modified date
	 *
	 * @param unknown_type $fieldFilters
	 * @return void
	 */
	function store( $fieldFilters = null ) {
		$this->setAttribute( 'modified', time() );
		parent::storeObject( $this, $fieldFilters );
	}

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	/**
	 * Create new OWNewsletterSending object
	 *
	 * @param OWNewsletter $newsletter
	 * @param OWNewsletterEdition $newsletterEdition
	 * @return object
	 */
	static function create( OWNewsletterEdition $newsletterEdition ) {
		$user = eZUser::currentUser();
		$creatorId = $user->attribute( 'contentobject_id' );
		$newsletter = $newsletterEdition->attribute( 'newsletter' );
		if ( !$newsletter instanceof OWNewsletter ) {
			throw new OWNewsletterException( "Fail to find newsletter configuration" );
		}
		$mailingList = $newsletterEdition->attribute( 'mailing_lists_ids' );
		if ( empty( $mailingList ) ) {
			throw new OWNewsletterException( "Mailing list is empty" );
		}
		$hashString = $newsletterEdition->attribute( 'mailing_lists_string' ) . $newsletterEdition->attribute( 'contentobject_id' ) . $newsletterEdition->attribute( 'contentobject_attribute_version' );

		$row = array(
			'edition_contentobject_id' => $newsletterEdition->attribute( 'contentobject_id' ),
			'edition_contentobject_version' => $newsletterEdition->attribute( 'contentobject_attribute_version' ),
			'newsletter_contentobject_id' => $newsletter->attribute( 'contentobject_id' ),
			'newsletter_contentobject_version' => $newsletter->attribute( 'contentobject_attribute_version' ),
			'mailing_lists_string' => $newsletterEdition->attribute( 'mailing_lists_string' ),
			'siteaccess' => $newsletter->attribute( 'main_siteaccess' ),
			'created' => time(),
			'creator_id' => $creatorId,
			'status' => self::STATUS_DRAFT,
			'hash' => OWNewsletterUtils::generateUniqueMd5Hash( $hashString ),
			'sender_email' => $newsletter->attribute( 'sender_email' ),
			'sender_name' => $newsletter->attribute( 'sender_name' ),
			'personalize_content' => $newsletter->attribute( 'personalize_content' )
		);
		$object = new OWNewsletterSending( $row );
		$object->setAttribute( 'serialized_output', $object->getSerializedOutput() );
		$object->store();
		return $object;
	}

	/**
	 * Mark sending as wait for preocess if possible
	 * 
	 * @param OWNewsletterEdition $newsletterEdition
	 */
	static function send( OWNewsletterEdition $newsletterEdition ) {
		$editionContentObjectID = $newsletterEdition->attribute( 'contentobject_id' );
		$sendingObject = self::fetch( $editionContentObjectID );
		if ( $sendingObject instanceof self && $sendingObject->attribute( 'status' ) == self::STATUS_DRAFT ) {
			$sendingObject->setAttribute( 'status', self::STATUS_WAIT_FOR_PROCESS );
			$sendingObject->store();
		}
	}

	/**
	 * Abort newsletter sending if possible
	 * 
	 * @param OWNewsletterEdition $newsletterEdition
	 */
	static function abort( OWNewsletterEdition $newsletterEdition ) {
		$editionContentObjectID = $newsletterEdition->attribute( 'contentobject_id' );
		$sendingObject = self::fetch( $editionContentObjectID );
		if ( $sendingObject instanceof self ) {
			if ( $sendingObject->attribute( 'can_abort' ) ) {
				$sendingObject->setAttribute( 'status', self::STATUS_ABORT );
				$sendingObject->abortSendingItems();
				$sendingObject->store();
			}
		}
	}

	static function sendTest( OWNewsletterEdition $newsletterEdition, $emailReceiverTest ) {
		if ( is_string( $emailReceiverTest ) ) {
			$emailReceiverTest = explode( ';', $emailReceiverTest );
		}
		$editionContentObjectID = $newsletterEdition->attribute( 'contentobject_id' );
		$sendingObject = self::fetch( $editionContentObjectID );
		if ( $sendingObject instanceof self ) {
			$newsletterMail = new OWNewsletterMail();
			return $newsletterMail->sendNewsletterTestMail( $sendingObject, $emailReceiverTest );
		}
	}

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */

	protected function abortSendingItems() {
		/* TODO */
	}

	/**
	 * Create a xml to save all rendered outputformats as a templatedraft so as
	 * later to send several newsletters
	 *
	 * @return serialized array
	 */
	public function getSerializedOutput() {
		$editionObject = $this->attribute( 'edition_object' );
		if ( !$editionObject instanceof OWNewsletterEdition ) {
			throw new OWNewsletterException( "Fail to find newsletter edition configuration" );
		}
		$newsletterObject = $this->attribute( 'newsletter_object' );
		if ( !$newsletterObject instanceof OWNewsletter ) {
			throw new OWNewsletterException( "Fail to find newsletter configuration" );
		}
		$mainSiteAccess = $newsletterObject->attribute( 'main_siteaccess' );
		$skinName = $newsletterObject->attribute( 'skin_name' );
		$forceNotIncludingImages = true;
		$output = $editionObject->getOutput( $mainSiteAccess, $skinName, $forceNotIncludingImages );
		$output['skin_name'] = $skinName;
		return serialize( $output );
	}

}
