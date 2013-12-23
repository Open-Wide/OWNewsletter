<?php

class OWNewsletterMailingListType extends eZDataType {

	const DATA_TYPE_STRING = 'ownewslettermailinglist';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function __construct() {
		$this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'newsletter/datatypes', 'Newsletter mailing list', 'Datatype name' ), array(
			'serialize_supported' => true, 'translation_allowed' => false ) );
	}

	/**
	 * Fetches all variables from the object
	 *
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#fetchObjectAttributeHTTPInput($http, $base, $objectAttribute)
	 * @return boolean
	 */
	function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute ) {

		$contentclassAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );

		$prefix = $base . '_ownewslettermailinglist_';
		$postfix = '_' . $contentObjectAttribute->attribute( 'id' );

		$postListData = array();
		$postListData['siteaccess_list'] = $http->hasPostVariable( $prefix . 'SiteaccessList' . $postfix ) ? $http->postVariable( $prefix . 'SiteaccessList' . $postfix ) : array();
		$postListData['auto_approve_registered_user'] = $http->postVariable( $prefix . 'AutoApproveRegisterdUser' . $postfix );
		

		$listObject = new OWNewsletterMailingList( array(
			'contentobject_attribute_id' => $contentObjectAttribute->attribute( 'id' ),
			'contentobject_attribute_version' => $contentObjectAttribute->attribute( 'version' ),
			'contentobject_id' => $contentObjectAttribute->attribute( 'contentobject_id' ),
			'contentclass_id' => $contentclassAttribute->attribute( 'contentclass_id' ),
			'siteaccess_list_string' => OWNewsletterMailingList::arrayToString( $postListData['siteaccess_list'] ),
			'auto_approve_registered_user' => $postListData['auto_approve_registered_user'],
				) );
		$contentObjectAttribute->setContent( $listObject );
		return true;
	}

	/**
	 * Sets the default value
	 *
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#initializeObjectAttribute($objectAttribute, $currentVersion, $originalContentObjectAttribute)
	 */
	function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute ) {
		if ( $currentVersion != false ) {
			$data = $originalContentObjectAttribute->attribute( 'content' );

			if ( $data instanceof OWNewsletterMailingList ) {
				$data->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
				$data->setAttribute( 'contentobject_attribute_version', $contentObjectAttribute->attribute( 'version' ) );
				$data->setAttribute( 'contentobject_id', $contentObjectAttribute->attribute( 'contentobject_id' ) );
				$contentObjectAttribute->setContent( $data );
				$contentObjectAttribute->store();
			}
		}
	}

	/**
	 * Returns the content
	 *
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#objectAttributeContent($objectAttribute)
	 */
	function objectAttributeContent( $contentObjectAttribute ) {
		$id = $contentObjectAttribute->attribute( 'id' );
		$version = $contentObjectAttribute->attribute( 'version' );

		$dataObject = OWNewsletterMailingList::fetch( $id, $version );
		if ( !is_object( $dataObject ) ) {
			$dataObject = new OWNewsletterMailingList();
			$dataObject->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
			$dataObject->setAttribute( 'contentobject_attribute_version', $contentObjectAttribute->attribute( 'version' ) );
			$dataObject->setAttribute( 'contentobject_id', $contentObjectAttribute->attribute( 'contentobject_id' ) );
		}
		return $dataObject;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#hasObjectAttributeContent($contentObjectAttribute)
	 * @return boolean
	 */
	function hasObjectAttributeContent( $contentObjectAttribute ) {
		if ( self::objectAttributeContent( $contentObjectAttribute ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns the meta data used for storing search indeces
	 *
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#metaData($contentObjectAttribute)
	 */
	function metaData( $contentObjectAttribute ) {
		// $geoData = $contentObjectAttribute->Content;
		// return $geoData->attribute('to_string');
		return '';
	}

	/**
	 * Returns the value as it will be shown if this attribute is used in the object name pattern
	 *
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#title($objectAttribute, $name)
	 */
	function title( $contentObjectAttribute, $name = null ) {
		$content = $contentObjectAttribute->attribute( 'content' );
		$mainSiteAccess = $content->attribute( 'main_siteaccess' );

		// enclose mainsiteaccess with '[]'
		$newSiteAccessArray = $content->attribute( 'siteaccess_list' );
		foreach ( $newSiteAccessArray as $index => $siteAccessName ) {
			if ( $siteAccessName == $mainSiteAccess ) {
				$newSiteAccessArray[$index] = '[' . $siteAccessName . ']';
			}
		}

		$listTitle = $contentObjectAttribute->attribute( 'contentobject_id' )
				. '; ' . implode( ', ', $content->attribute( 'output_format_array' ) )
				. '; A' . $content->attribute( 'auto_approve_registered_user' )
				. '; P' . $content->attribute( 'personalize_content' )
				. '; ' . implode( ', ', $newSiteAccessArray );
		return $listTitle;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#isIndexable()
	 * @return boolean
	 */
	function isIndexable() {
		return false;
	}

	/**
	 * Store the content. Since the content has been stored in function
	 * fetchObjectAttributeHTTPInput(), this function is with empty code
	 *
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#storeObjectAttribute($objectAttribute)
	 */
	function storeObjectAttribute( $contentObjectAttribute ) {
		$object = $contentObjectAttribute->Content;
		if ( is_object( $object ) ) {
			$object->store();
			return true;
		}
		return false;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#deleteStoredObjectAttribute($objectAttribute, $version)
	 */
	function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null ) {
		$object = OWNewsletterMailingList::fetch( $contentObjectAttribute->attribute( "id" ), $contentObjectAttribute->attribute( "version" ) );
		if ( is_object( $object ) ) {
			$object->remove();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#serializeContentObjectAttribute($package, $objectAttribute)
	 */
	function serializeContentObjectAttribute( $package, $objectAttribute ) {
		$dom = new DOMDocument( '1.0', 'utf-8' );
		$node = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:attribute' );
		$node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:id', $objectAttribute->attribute( 'id' ) );
		$node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:identifier', $objectAttribute->contentClassAttributeIdentifier() );
		$node->setAttribute( 'name', $objectAttribute->contentClassAttributeName() );
		$node->setAttribute( 'type', $this->isA() );

		if ( $this->Attributes["properties"]['object_serialize_map'] ) {
			$map = $this->Attributes["properties"]['object_serialize_map'];
			foreach ( $map as $attributeName => $xmlName ) {
				if ( $objectAttribute->hasAttribute( $attributeName ) ) {
					$value = $objectAttribute->attribute( $attributeName );
					unset( $attributeNode );
					$attributeNode = $dom->createElement( $xmlName, (string) $value );
					$node->appendChild( $attributeNode );
				} else {
					eZDebug::writeError( "The attribute '$attributeName' does not exists for contentobject attribute " . $objectAttribute->attribute( 'id' ), 'eZDataType::serializeContentObjectAttribute' );
				}
			}
		} else {
			$cjwNewsletterContent = $objectAttribute->attribute( 'content' );
			$cjwNewsletterContentSerialized = serialize( $cjwNewsletterContent );
			$dataTextNode = $dom->createElement( 'ownewslettermailinglist' );
			$serializedNode = $dom->createCDATASection( $cjwNewsletterContentSerialized );
			$dataTextNode->appendChild( $serializedNode );
			$node->appendChild( $dataTextNode );
		}
		return $node;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#unserializeContentObjectAttribute($package, $objectAttribute, $attributeNode)
	 */
	function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode ) {
		$contentclassAttribute = $objectAttribute->attribute( 'contentclass_attribute' );

		if ( $this->Attributes["properties"]['object_serialize_map'] ) {
			$map = $this->Attributes["properties"]['object_serialize_map'];
			foreach ( $map as $attributeName => $xmlName ) {
				if ( $objectAttribute->hasAttribute( $attributeName ) ) {
					$elements = $attributeNode->getElementsByTagName( $xmlName );
					if ( $elements->length !== 0 ) {
						$value = $elements->item( 0 )->textContent;
						$objectAttribute->setAttribute( $attributeName, $value );
					} else {
						eZDebug::writeError( "The xml element '$xmlName' does not exist for contentobject attribute " . $objectAttribute->attribute( 'id' ), 'eZDataType::unserializeContentObjectAttribute' );
					}
				} else {
					eZDebug::writeError( "The attribute '$attributeName' does not exist for contentobject attribute " . $objectAttribute->attribute( 'id' ), 'eZDataType::unserializeContentObjectAttribute' );
				}
			}
		} else {
			$oWNewsletterMailingListObjectSerialized = $attributeNode->getElementsByTagName( 'ownewslettermailinglist' )->item( 0 )->textContent;
			$oWNewsletterMailingListObject = unserialize( $oWNewsletterMailingListObjectSerialized );

			if ( is_object( $oWNewsletterMailingListObject ) ) {
				$oWNewsletterMailingListObject->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
				$oWNewsletterMailingListObject->setAttribute( 'contentobject_attribute_version', $objectAttribute->attribute( 'version' ) );
				$oWNewsletterMailingListObject->setAttribute( 'contentobject_id', $objectAttribute->attribute( 'contentobject_id' ) );
				$oWNewsletterMailingListObject->setAttribute( 'contentclass_id', $contentclassAttribute->attribute( 'contentclass_id' ) );
				$oWNewsletterMailingListObject->store();
				$objectAttribute->setAttribute( 'content', $oWNewsletterMailingListObject );
			} else {
				$objectAttribute->setAttribute( 'content', null );
			}
		}
	}

	/**
	 * Return string representation of an contentobjectattribute data for simplified export
	 *
	 * @see kernel/classes/eZDataType#toString($objectAttribute)
	 */
	function toString( $contentObjectAttribute ) {
		return serialize( $contentObjectAttribute->attribute( 'content' ) );
	}

	function fromString( $contentObjectAttribute, $string ) {
		return $contentObjectAttribute->setAttribute( 'content', unserialize( $string ) );
	}

}

eZDataType::register( OWNewsletterMailingListType::DATA_TYPE_STRING, 'OWNewsletterMailingListType' );

