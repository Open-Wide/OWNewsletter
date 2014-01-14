<?php

class OWNewsletterEdition extends eZPersistentObject {

	const STATUS_DRAFT = 'draft';
	const STATUS_PROCESS = 'process';  // sending
	const STATUS_ARCHIVE = 'archive';  // archived
	const STATUS_ABORT = 'abort';   // aborted

	/**
	 * Constructor
	 *
	 * @param array $row
	 * @return void
	 */

	public function __construct( $row = array() ) {
		$this->eZPersistentObject( $row );
	}

	/**
	 * @return void
	 */
	static public function definition() {
		return array( 'fields' => array(
				'contentobject_attribute_id' => array(
					'name' => 'ContentObjectAttributeId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'contentobject_attribute_version' => array(
					'name' => 'ContentObjectAttributeVersion',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'contentobject_id' => array(
					'name' => 'ContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'contentclass_id' => array(
					'name' => 'ContentClassId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'mailing_lists_string' => array(
					'name' => 'MailingListsString',
					'datatype' => 'string',
					'default' => 'default',
					'required' => true )
			),
			'keys' => array( 'contentobject_attribute_id', 'contentobject_attribute_version' ),
			'function_attributes' => array(
				'mailing_lists_ids' => 'getMailingListIDs',
				'available_mailing_lists' => 'getAvailableMailingLists',
				'newsletter' => 'getNewsletter',
				'sending' => 'getSending',
				'status' => 'getStatus',
				'status_name' => 'getStatusName'
			),
			'class_name' => 'OWNewsletterEdition',
			'name' => 'ownl_edition' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * Transform string to array for mailing_lists_ids attribute
	 * 
	 * @return array
	 */
	public function getMailingListIDs() {
		return OWNewsletterUtils::stringToArray( $this->attribute( 'mailing_lists_string' ) );
	}

	/**
	 * Returns the list of available mailing lists
	 *
	 * @return array
	 */
	public function getAvailableMailingLists() {
		$contentObject = eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
		$currentContentObject = $contentObject->attribute('current');
		$contentObjectNewsletterEditionNode = $currentContentObject->attribute( 'temp_main_node' );
		if ( $contentObjectNewsletterEditionNode instanceof eZContentObjectTreeNode ) {
			$contentObjectNewsletterNode = $contentObjectNewsletterEditionNode->attribute( 'parent' );
			$contentObjectNewsletterSystemNodeID = $contentObjectNewsletterNode->attribute( 'parent_node_id' );
			$mailingListList = eZFunctionHandler::execute( 'content', 'tree', array(
						'parent_node_id' => $contentObjectNewsletterSystemNodeID,
						'class_filter_type' => 'include',
						'class_filter_array' => array( 'newsletter_mailing_list' ),
						'sort_by' => array( 'name', false )
					) );
			return $mailingListList;
		}
		return array();
	}

	/**
	 * Returns the list of default mailing lists
	 *
	 * @return array
	 */
	public function getNewsletter() {
		$contentObject = eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
		$currentContentObject = $contentObject->attribute('current');
		$contentObjectNewsletterEditionNode = $currentContentObject->attribute( 'temp_main_node' );
		if ( $contentObjectNewsletterEditionNode instanceof eZContentObjectTreeNode ) {
			$contentObjectNewsletterNode = $contentObjectNewsletterEditionNode->attribute( 'parent' );
			$dataMap = $contentObjectNewsletterNode->dataMap();
			foreach ( $dataMap as $attribute ) {
				if ( $attribute->attribute( 'data_type_string' ) == 'ownewsletter' ) {
					return $attribute->content();
				}
			}
		}
	}

	/**
	 * Returns OWNewsletterSending of the edition
	 */
	public function getSending() {
		$sending = OWNewsletterSending::fetch( $this->attribute( 'contentobject_id' ) );
		if ( !$sending instanceof OWNewsletterSending ) {
			try {
				$sending = OWNewsletterSending::create( $this );
			} catch ( OWNewsletterException $e ) {
				return false;
			}
		}
		return $sending;
	}

	/**
	 * Return status identifier of newsletter edition
	 * 
	 * @return string
	 */
	public function getStatus() {
		$sending = $this->attribute( 'sending' );
		if ( $sending instanceof OWNewsletterSending ) {
			if ( $sending->attribute( 'status' ) == OWNewsletterSending::STATUS_DRAFT ) {
				return self::STATUS_DRAFT;
			} elseif ( $sending->attribute( 'status' ) == OWNewsletterSending::STATUS_ABORT ) {
				return self::STATUS_ABORT;
			} elseif ( $sending->attribute( 'status' ) == OWNewsletterSending::STATUS_MAILQUEUE_PROCESS_FINISHED ) {
				return self::STATUS_ARCHIVE;
			} else {
				return self::STATUS_PROCESS;
			}
		}
		return self::STATUS_DRAFT;
	}

	/**
	 * Return status identifier of newsletter edition
	 * 
	 * @return string
	 */
	public function getStatusName() {
		switch ( $this->attribute( 'status' ) ) {
			case self::STATUS_DRAFT:
				return ezpI18n::tr( 'newsletter/edition/status', 'Draft' );
			case self::STATUS_PROCESS:
				return ezpI18n::tr( 'newsletter/edition/status', 'Sending' );
			case self::STATUS_ARCHIVE:
				return ezpI18n::tr( 'newsletter/edition/status', 'Archived' );
			case self::STATUS_ABORT:
				return ezpI18n::tr( 'newsletter/edition/status', 'Aborted' );
			default:
				break;
		}
	}

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Return object by attribute id and version
	 *
	 * @param integer $attributeId
	 * @param integer $version
	 * @return object or boolean
	 */
	static public function fetch( $attributeId, $version ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array(
					'contentobject_attribute_id' => $attributeId,
					'contentobject_attribute_version' => $version ), true );
		return $object;
	}

	/**
	 * Return last version object by content object id
	 *
	 * @param integer $attributeId
	 * @param integer $version
	 * @return object or boolean
	 */
	static public function fetchLastVersion( $objectId ) {
		$rows = eZPersistentObject::fetchObjectList( self::definition(), null, array(
					'contentobject_id' => $objectId ), array( 'contentobject_attribute_version' => 'desc' ) );
		if ( $rows ) {
			return $rows[0];
		}
		return null;
	}

	/**
	 * Return object by custom conditions
	 *
	 * @param integer $attributeId
	 * @param integer $version
	 * @return object or boolean
	 */
	static public function fetchByCustomConditions( $conds ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, $conds, true );
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
		$sortArr = null;
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

	/*	 * **********************
	 * OBJECT METHODS
	 * ********************** */

	/**
	 * Store object in the database
	 * 
	 * @param type $fieldFilters
	 */
	public function store( $fieldFilters = null ) {
		$sending = $this->attribute( 'sending' );
		try {
			if ( $sending instanceof OWNewsletterSending && $sending->attribute( 'status' ) == OWNewsletterSending::STATUS_DRAFT ) {
				OWNewsletterSending::create( $this );
			} elseif ( !$sending instanceof OWNewsletterSending ) {
				OWNewsletterSending::create( $this );
			}
		} catch ( OWNewsletterException $e ) {
			eZDebug::writeError( "Fail to create sending object : " . $e->getMessage(), "Newsletter edition store" );
		}
		parent::store( $fieldFilters );
	}

	/**
	 * Remove object and all its subscriptions if last
	 * 
	 * @param type $conditions
	 * @param type $extraConditions
	 */
	public function remove( $conditions = null, $extraConditions = null ) {
		parent::remove( $conditions, $extraConditions );
		if ( self::count( self::definition(), array( 'contentobject_id' => $this->attribute( 'contentobject_id' ) ) ) == 0 ) {
			if ( $this->attribute( 'sending' ) instanceof OWNewsletterSending ) {
				$this->attribute( 'sending' )->remove();
			}
		}
	}

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */

	/**
	 * read newsletter.ini and return true if images should inlcude in emails
	 * @return unknown_type
	 */
	static function imageIncludeIsEnabled() {
		$newsletterINI = eZINI::instance( 'newsletter.ini' );
		$imageInclude = $newsletterINI->variable( 'NewsletterMailSettings', 'ImageInclude' );
		return $imageInclude === 'enabled';
	}

}
