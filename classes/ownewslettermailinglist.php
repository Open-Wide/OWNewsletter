<?php

class OWNewsletterMailingList extends eZPersistentObject {

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
		return array( 'fields' => array( 'contentobject_attribute_id' => array(
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
				'siteaccess_list_string' => array(
					'name' => 'SiteAccessListString',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'auto_approve_registered_user' => array(
					'name' => 'AutoApproveRegisterdUser',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
			),
			'keys' => array( 'contentobject_attribute_id', 'contentobject_attribute_version' ),
			'function_attributes' => array(
				'siteaccess_list' => 'getSiteaccessList',
				'available_siteaccess_list' => 'getAvailableSiteAccessList',
			),
			'class_name' => 'OWNewsletterMailingList',
			'name' => 'ownl_mailing_list' );
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * Transfert the siteaccess_list_string attribute in array
	 * 
	 * @return array
	 */
	function getSiteaccessList() {
		return OWNewsletterUtils::stringToArray( $this->attribute( 'siteaccess_list_string' ) );
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

}

?>
