<?php

class OWNewsletterEdition extends eZPersistentObject {

	const STATUS_DRAFT = 'draft';
    const STATUS_PROCESS = 'process';  // sending
    const STATUS_ARCHIVE = 'archive';  // archived
    const STATUS_ABORT = 'abort';      // aborted
	
	/**
	 * @return void
	 */
	static function definition() {
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
					'name' => 'MailingListSendingListString',
					'datatype' => 'string',
					'default' => 'default',
					'required' => true )
			),
			'keys' => array( 'contentobject_attribute_id', 'contentobject_attribute_version' ),
			'function_attributes' => array(
				'mailing_lists_ids' => 'getMailingListIDs',
				'available_mailing_lists' => 'getAvailableMailingLists',
				'newsletter' => 'getNewsletter',
				'status' => 'getStatus'
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
	function getAvailableMailingLists() {
		$contentObject = eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
		$contentObjectMainNode = $contentObject->attribute( 'main_node' );
		$contentObjectParentNodeID = $contentObjectMainNode->attribute( 'parent' )->attribute( 'parent_node_id' );
		$mailingListList = eZFunctionHandler::execute( 'content', 'tree', array(
					'parent_node_id' => $contentObjectParentNodeID,
					'class_filter_type' => 'include',
					'class_filter_array' => array( 'newsletter_mailing_list' ),
					'sort_by' => array( 'name', false )
				) );
		return $mailingListList;
	}

	/**
	 * Returns the list of default mailing lists
	 *
	 * @return array
	 */
	function getNewsletter() {
		$contentObject = eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
		$contentObjectMainNode = $contentObject->attribute( 'main_node' );
		$contentObjectParentNode = $contentObjectMainNode->attribute( 'parent' );
		$dataMap = $contentObjectParentNode->dataMap();
		foreach ( $dataMap as $attribute ) {
			if ( $attribute->attribute( 'data_type_string' ) == 'ownewsletter' ) {
				return $attribute->content();
			}
		}
	}
	
	/**
	 * Return status identifier of newsletter edition
	 * 
	 * @return string
	 */
	function getStatus() {
		return self::STATUS_DRAFT;
	}
	

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Return object by id
	 *
	 * @param integer $attributeId
	 * @param integer $version
	 * @return object or boolean
	 */
	static function fetch( $attributeId, $version ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array(
					'contentobject_attribute_id' => $attributeId,
					'contentobject_attribute_version' => $version ), true );
		return $object;
	}

	/**
	 * Return object by custom conditions
	 *
	 * @param integer $attributeId
	 * @param integer $version
	 * @return object or boolean
	 */
	static function fetchByCustomConditions( $conds ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, $conds, true );
		return $object;
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
}

?>