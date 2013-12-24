<?php

class OWNewsletter extends eZPersistentObject {

	/**
	 * Initializes a new GeoadressData alias
	 *
	 * @param unknown_type $row
	 * @return void
	 */
	function OWNewsletter( $row = array() ) {
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
				'email_sender_name' => array(
					'name' => 'EmailSenderName',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'email_sender' => array(
					'name' => 'EmailSender',
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
				'default_mailing_list_selection_string' => array(
					'name' => 'DefaultMailingListSelectionString',
					'datatype' => 'string',
					'default' => 'default',
					'required' => true ),
			),
			'keys' => array( 'contentobject_attribute_id', 'contentobject_attribute_version' ),
			'function_attributes' => array(
				'default_mailing_list_selection' => 'getDefaultMailingListSelection',
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
	 * Transform string to array for default_mailing_list_selection attribute
	 * 
	 * @return array
	 */
	public function getDefaultMailingListSelection() {
		return self::stringToArray( eZPersistentObject::attribute( 'default_mailing_list_selection_string' ) );
	}

	/**
	 * Transform string to array for email_receiver_test_list attribute
	 * 
	 * @return array
	 */
	public function getEmailReceiverTestList() {
		return self::stringToArray( eZPersistentObject::attribute( 'email_receiver_test' ) );
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
		$availableSkinList = $newsletterIni->variable( 'NewsletterSettings', 'AvailableSkinArray' );
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
					'sort_by' => array( 'name', false )
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
	 * Convert array to string
	 * ;$1;$2;$3;
	 * for searching : begin and end is ";"
	 * like %;$1;%
	 *
	 * @param array $array
	 * @return string
	 */
	static function arrayToString( $array ) {
		return ';' . implode( ';', $array ) . ';';
	}

	/**
	 * Convert string to array
	 * ;$1;$2;$3; to array( $1, $2, $3 )
	 *
	 * @param $string
	 * @return unknown_type
	 */
	static function stringToArray( $string ) {
		return explode( ';', substr( $string, 1, strlen( $string ) - 2 ) );
	}

}
