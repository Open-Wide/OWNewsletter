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
	public function getNewsletter() {
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
	public function getStatus() {
		return self::STATUS_DRAFT;
	}

	/**
	 * Return status identifier of newsletter edition
	 * 
	 * @return string
	 */
	public function getStatusName() {
		return ezpI18n::tr( 'newsletter/edition/status', 'Draft' );
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
	static public function fetch( $attributeId, $version ) {
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

	public function getOutput( $siteAccess, $skinName = 'default', $forceSettingImageIncludeTo = -1 ) {
		$editionContentObjectId = $this->attribute( 'contentobject_id' );
		$versionId = $this->attribute( 'contentobject_attribute_version' );
		if ( $skinName == '' ) {
			$skinName = 'default';
		}

		$cjwNewsletterIni = eZINI::instance( 'newsletter.ini' );
		$phpCli = $cjwNewsletterIni->variable( 'NewsletterSettings', 'PhpCli' );

		$currentHostName = eZSys::hostname();
		$wwwDir = eZSys::wwwDir();

		$wwwDirString = '';
		if ( $wwwDir != '' ) {
			$wwwDirString = "--www_dir=$wwwDir ";
		}

		$cmd = "\"$phpCli\" extension/ownewsletter/bin/php/createoutput.php --object_id=$editionContentObjectId --object_version=$versionId $wwwDirString--current_hostname=$currentHostName --skin_name=$skinName -s $siteAccess";

		$fileSep = eZSys::fileSeparator();
		$cmd = str_replace( '/', $fileSep, $cmd );

		eZDebug::writeDebug( "shell_exec( $cmd )", 'newsletter/preview' );

		$returnValue = shell_exec( escapeshellcmd( $cmd ) );
		$newsletterContentArray = unserialize( trim( $returnValue ) );

		$imageInclude = false;

// render file:// if we want to force it
// or use setting from $newsletterContentArray['html_mail_image_include']


		if ( $forceSettingImageIncludeTo === -1 && $newsletterContentArray['html_mail_image_include'] === 1 ) {
			$imageInclude = true;
		} elseif ( $forceSettingImageIncludeTo === 1 ) {
			$imageInclude = true;
		}

		if ( $imageInclude === true ) {
			$newsletterContentArray = self::prepareImageInclude( $newsletterContentArray );
		}

		return $newsletterContentArray;
	}

	/**
	 * prepare string => find local img files and replace http:// to file:// so it will be included by ezcomponents into the mail
	 *
	 * @param $newsletterContentArray
	 * @return unknown_type
	 */
	static function prepareImageInclude( $newsletterContentArray ) {
		$newsletterContentArrayNew = $newsletterContentArray;

		$eZRoot = $newsletterContentArray['ez_root'] . '/';
		$eZFile = 'file://ezroot/';
		$body = $newsletterContentArray['body'];
		foreach ( $body as $id => $value ) {
			// replace all image src from http => file:\\ezroot\ so OWNewsletterMailComposer will embed it into the mail message
			if ( $id === 'html' ) {
				$newsletterContentArrayNew['body'][$id] = str_replace( "src=\"$eZRoot", "src=\"$eZFile", $value );
			}
		}
		return $newsletterContentArrayNew;
	}

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
