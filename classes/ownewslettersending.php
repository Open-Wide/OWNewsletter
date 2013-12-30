<?php

class OWNewsletterSending extends eZPersistentObject {

	const STATUS_WAIT_FOR_PROCESS = 0;
	const STATUS_MAILQUEUE_CREATED = 1;
	const STATUS_MAILQUEUE_PROCESS_STARTED = 2;
	const STATUS_MAILQUEUE_PROCESS_FINISHED = 3;
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
				'output_xml' => array( 'name' => 'OutputXml',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'hash' => array( 'name' => 'Hash',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'email_sender' => array( 'name' => 'EmailSender',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'email_sender_name' => array( 'name' => 'EmailSenderName',
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
			),
			'class_name' => 'OWNewsletterSending',
			'name' => 'ownl_sending' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	public function getNewsletterObject() {
		return OWNewsletter::fetchByCustomConditions( array(
					'contentobject_id' => $this->attribute( 'newsletter_contentobject_id' ),
					'contentobject_attribute_version' => $this->attribute( 'newsletter_contentobject_version' )
				) );
	}

	public function getEditionObject() {
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
					if ( $value === self::STATUS_MAILQUEUE_CREATED ) {
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
	static function create( OWNewsletter $newsletter, OWNewsletterEdition $newsletterEdition ) {
		$user = eZUser::currentUser();
		$creatorId = $user->attribute( 'contentobject_id' );

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
			'status' => self::STATUS_WAIT_FOR_PROCESS,
			'hash' => OWNewsletterUtils::generateUniqueMd5Hash( $hashString ),
			'email_sender' => $newsletter->attribute( 'email_sender' ),
			'email_sender_name' => $newsletter->attribute( 'email_sender_name' ),
			'personalize_content' => $newsletter->attribute( 'personalize_content' )
		);
		$object = new OWNewsletterSending( $row );
		$object->setAttribute( 'output_xml', $object->getOutputXml() );
		$object->store();
		return $object;
	}

	static function abort( OWNewsletterEdition $newsletterEdition ) {
		$editionContentObjectID = $newsletterEdition->attribute( 'contentobject_id' );
		$sendingObject = self::fetch( $editionContentObjectID );
		if ( $sendingObject instanceof self ) {
			if ( $sendingObject->attribute( 'can_abort' ) ) {
				$this->setAttribute( 'status', self::STATUS_ABORT );
				$this->abortSendingItems();
			}
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
	 * @return xml
	 */
	public function getOutputXml() {
		$newsletterObject = $this->attribute( 'newsletter_object' );
		$editionObject = $this->attribute( 'edition_object' );

		$dom = new DOMDocument( '1.0', 'utf-8' );
		$root = $dom->createElement( 'newsletter_sending' );
		$root = $dom->appendChild( $root );

		// in first version attribut xml_version did not exists
		$root->setAttribute( 'xml_version', '2' );

		$root->setAttribute( 'edition_contentobject_id', $editionObject->attribute( 'contentobject_id' ) );
		$root->setAttribute( 'edition_contentobject_version', $editionObject->attribute( 'contentobject_attribute_version' ) );
		$root->setAttribute( 'main_siteaccess', $newsletterObject->attribute( 'main_siteaccess' ) );

		$mainSiteAccess = $newsletterObject->attribute( 'main_siteaccess' );
		$skinName = $newsletterObject->attribute( 'skin_name' );

		$forceNotIncludingImages = true;
		$textArray = $editionObject->getOutput( $mainSiteAccess, $skinName, $forceNotIncludingImages );

		$output = $dom->createElement( 'output' );
		$output->setAttribute( 'content_type', trim( $textArray['content_type'] ) );
		$output->setAttribute( 'subject', trim( $textArray['subject'] ) );
		$output->setAttribute( 'site_url', trim( $textArray['site_url'] ) );
		$output->setAttribute( 'ez_url', trim( $textArray['ez_url'] ) );
		$output->setAttribute( 'ez_root', trim( $textArray['ez_root'] ) );
		$output->setAttribute( 'html_mail_image_include', $textArray['html_mail_image_include'] );
		$output->setAttribute( 'locale', trim( $textArray['locale'] ) );

		$root->appendChild( $output );

		$mainTemplateNode = $dom->createElement( 'main_template' );
		foreach ( $textArray['body'] as $typeName => $outputString ) {
			$typeNode = $dom->createElement( 'type' );
			$typeNode->setAttribute( 'name', $typeName );
			$typeNodeCDATA = $dom->createCDATASection( $outputString );
			$typeNode->appendChild( $typeNodeCDATA );
			$mainTemplateNode->appendChild( $typeNode );
		}
		$output->appendChild( $mainTemplateNode );

		$xml = $dom->saveXML();
		return $xml;
	}

}
