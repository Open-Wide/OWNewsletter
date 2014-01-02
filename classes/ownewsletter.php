<?php

class OWNewsletter extends eZPersistentObject {

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
				'main_siteaccess' => array(
					'name' => 'MainSiteAccess',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'sender_name' => array(
					'name' => 'SenderName',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'sender_email' => array(
					'name' => 'SenderEmail',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'email_receiver_test' => array(
					'name' => 'EmailReceiverTest',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'skin_name' => array(
					'name' => 'SkinName',
					'datatype' => 'string',
					'default' => 'default',
					'required' => true ),
				'personalize_content' => array(
					'name' => 'PersonalizeContent',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'user_data_fields' => array(
					'name' => 'UserDataFields',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'default_mailing_lists_string' => array(
					'name' => 'DefaultMailingListsString',
					'datatype' => 'string',
					'default' => 'default',
					'required' => true ),
			),
			'keys' => array( 'contentobject_attribute_id', 'contentobject_attribute_version' ),
			'function_attributes' => array(
				'default_mailing_lists_ids' => 'getDefaultMailingListsIDs',
				'email_receiver_test_list' => 'getEmailReceiverTestList',
				'available_siteaccess_list' => 'getAvailableSiteAccessList',
				'available_skin_list' => 'getAvailableSkinList',
				'available_mailing_lists' => 'getAvailableMailingLists' ),
			'class_name' => 'OWNewsletter',
			'name' => 'ownl_newsletter' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * Transform string to array for default_mailing_lists_ids attribute
	 * 
	 * @return array
	 */
	public function getDefaultMailingListsIDs() {
		return OWNewsletterUtils::stringToArray( $this->attribute( 'default_mailing_lists_string' ) );
	}

	/**
	 * Transform string to array for email_receiver_test_list attribute
	 * 
	 * @return array
	 */
	public function getEmailReceiverTestList() {
		return OWNewsletterUtils::stringToArray( $this->attribute( 'email_receiver_test' ) );
	}

	/**
	 * Returns current siteaccess + language-info + siteURL
	 *
	 * @return array
	 */
	function getAvailableSiteaccessList() {
		$ini = eZINI::instance( 'site.ini' );
		$availableSiteAccessListArray = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
		$availableSiteAccessListInfoArray = array();

		foreach ( $availableSiteAccessListArray as $siteAccessName ) {
			$siteIni = eZINI::getSiteAccessIni( $siteAccessName, 'site.ini' );
			$locale = '-';
			$siteUrl = '-';
			if ( is_object( $siteIni ) ) {
				$locale = $siteIni->variable( 'RegionalSettings', 'Locale' );
				$siteUrl = $siteIni->variable( 'SiteSettings', 'SiteURL' );
			}
			$availableSiteAccessListInfoArray[$siteAccessName] = array(
				'name' => $siteAccessName,
				'locale' => $locale,
				'site_url' => $siteUrl );
		}
		return $availableSiteAccessListInfoArray;
	}

	/**
	 * Returns the list of available skins
	 *
	 * @return array
	 */
	function getAvailableSkinList() {

		$newsletterIni = eZINI::instance( 'newsletter.ini' );
		$availableSkinList = $newsletterIni->variable( 'NewsletterSettings', 'AvailableSkinList' );
		return $availableSkinList;
	}

	/**
	 * Returns the list of available mailing lists
	 *
	 * @return array
	 */
	function getAvailableMailingLists() {
		$contentObject = eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
		$contentObjectMainNode = $contentObject->attribute( 'main_node' );
		$contentObjectParentNodeID = $contentObjectMainNode->attribute( 'parent_node_id' );
		$mailingListList = eZFunctionHandler::execute( 'content', 'tree', array(
					'parent_node_id' => $contentObjectParentNodeID,
					'class_filter_type' => 'include',
					'class_filter_array' => array( 'newsletter_mailing_list' ),
					'sort_by' => array( 'name', true )
				) );
		return $mailingListList;
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
