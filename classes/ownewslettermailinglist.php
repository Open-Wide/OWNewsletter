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
				'subscriptions' => 'getSubscriptions',
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

	/**
	 * Returns all subscriptions of the mailing list
	 */
	public function getSubscriptions() {
		return OWNewsletterSubscription::fetchList( array( 'mailing_list_contentobject_id' => $this->attribute( 'contentobject_id' ) ) );
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

	/*	 * **********************
	 * OBJECT METHODS
	 * ********************** */

	/**
	 * Remove object and all its subscriptions if last
	 * 
	 * @param type $conditions
	 * @param type $extraConditions
	 */
	public function remove( $conditions = null, $extraConditions = null ) {
		parent::remove( $conditions, $extraConditions );
		if ( self::count( self::definition(), array( 'contentobject_id' => $this->attribute( 'contentobject_id' ) ) ) == 0 ) {
			foreach ( $this->attribute( 'subscriptions' ) as $subscriptions ) {
				$subscriptions->remove();
			}
		}
	}

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */
}

?>
