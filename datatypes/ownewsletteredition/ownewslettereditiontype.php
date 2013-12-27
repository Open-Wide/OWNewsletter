<?php

class OWNewsletterEditionType extends eZDataType {

	const DATA_TYPE_STRING = 'ownewsletteredition';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	function __construct() {
		$this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'newsletter/datatypes', 'Newsletter Edition', 'Datatype name' ), array(
			'serialize_supported' => true, 'translation_allowed' => false ) );
	}

	/**
	 * Validates input on content object level
	 *
	 *
	 * @see kernel/classes/eZDataType#validateObjectAttributeHTTPInput($http, $base, $objectAttribute)
	 * @return EZ_INPUT_VALIDATOR_STATE
	 */
	function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute ) {
		$prefix = $base . '_ownewsletteredition_';
		$postfix = '_' . $contentObjectAttribute->attribute( 'id' );

		$mailingListSendingList = $http->postVariable( $prefix . 'MailingListSendingList' . $postfix );
		if ( empty( $mailingListSendingList ) ) {
			$contentObjectAttribute->setValidationError( ezpI18n::tr( 'newsletter/datatype/ownewsletter', "Sending mailing list must be set" ) );
			return eZInputValidator::STATE_INVALID;
		}
		return eZInputValidator::STATE_ACCEPTED;
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
		$prefix = $base . '_ownewsletteredition_';
		$postfix = '_' . $contentObjectAttribute->attribute( 'id' );

		$newsletterEditionObject = new OWNewsletterEdition( array(
			'contentobject_attribute_id' => $contentObjectAttribute->attribute( 'id' ),
			'contentobject_attribute_version' => $contentObjectAttribute->attribute( 'version' ),
			'contentobject_id' => $contentObjectAttribute->attribute( 'contentobject_id' ),
			'contentclass_id' => $contentclassAttribute->attribute( 'contentclass_id' ),
			'mailing_lists_string' => OWNewsletterUtils::arrayToString( (array) $http->postVariable( $prefix . 'MailingListSendingList' . $postfix ) ),
				) );
		$contentObjectAttribute->setContent( $newsletterEditionObject );
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
			$data = $originalContentObjectAttribute->attribute( "content" );

			if ( $data instanceof OWNewsletterEdition ) {
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

		$dataObject = OWNewsletterEdition::fetch( $id, $version );
		if ( !is_object( $dataObject ) ) {
			$dataObject = new OWNewsletterEdition();
			$dataObject->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
			$dataObject->setAttribute( 'contentobject_attribute_version', $contentObjectAttribute->attribute( 'version' ) );
			$dataObject->setAttribute( 'contentobject_id', $contentObjectAttribute->attribute( 'contentobject_id' ) );
		}
		return $dataObject;
	}

	/**
	 *
	 *
	 * @see kernel/classes/eZDataType#hasObjectAttributeContent($contentObjectAttribute)
	 * @return boolean
	 */
	function hasObjectAttributeContent( $contentObjectAttribute ) {
		if ( OWNewsletterEditionType::objectAttributeContent( $contentObjectAttribute ) ) {
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
		return '';
	}

	/**
	 * Returns the value as it will be shown if this attribute is used in the object name pattern
	 *
	 * @see kernel/classes/eZDataType#title($objectAttribute, $name)
	 */
	function title( $contentObjectAttribute, $name = null ) {
		return "Newsletter edition title";
	}

	/**
	 *
	 * @see kernel/classes/eZDataType#isIndexable()
	 */
	function isIndexable() {
		return false;
	}

	/**
	 * Store the content. Since the content has been stored in function
	 * fetchObjectAttributeHTTPInput(), this function is with empty code
	 *
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
	 * @see kernel/classes/eZDataType#deleteStoredObjectAttribute($objectAttribute, $version)
	 */
	function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null ) {
		$object = OWNewsletterEdition::fetch( $contentObjectAttribute->attribute( "id" ), $contentObjectAttribute->attribute( "version" ) );
		if ( is_object( $object ) ) {
			$object->remove();
		}
	}

	/**
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
			$newsletterContent = $objectAttribute->attribute( 'content' );
			$newsletterContentSerialized = serialize( $newsletterContent );
			$dataTextNode = $dom->createElement( 'ownewsletteredition' );
			$serializedNode = $dom->createCDATASection( $newsletterContentSerialized );
			$dataTextNode->appendChild( $serializedNode );
			$node->appendChild( $dataTextNode );
		}
		return $node;
	}

	/**
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
			$oWNewsletterEditionObjectSerialized = $attributeNode->getElementsByTagName( 'ownewsletteredition' )->item( 0 )->textContent;
			$oWNewsletterEditionObject = unserialize( $oWNewsletterEditionObjectSerialized );

			if ( is_object( $oWNewsletterEditionObject ) ) {
				$oWNewsletterEditionObject->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
				$oWNewsletterEditionObject->setAttribute( 'contentobject_attribute_version', $objectAttribute->attribute( 'version' ) );
				$oWNewsletterEditionObject->setAttribute( 'contentobject_id', $objectAttribute->attribute( 'contentobject_id' ) );
				$oWNewsletterEditionObject->setAttribute( 'contentclass_id', $contentclassAttribute->attribute( 'contentclass_id' ) );
				$oWNewsletterEditionObject->store();
				$objectAttribute->setAttribute( 'content', $oWNewsletterEditionObject );
			} else {
				$objectAttribute->setAttribute( 'content', null );
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#toString($objectAttribute)
	 * @return string
	 */
	function toString( $contentObjectAttribute ) {
		return serialize( $contentObjectAttribute->attribute( 'content' ) );
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/classes/eZDataType#fromString($objectAttribute, $string)
	 */
	function fromString( $contentObjectAttribute, $string ) {
		return $contentObjectAttribute->setAttribute( 'content', unserialize( $string ) );
	}

}

eZDataType::register( OWNewsletterEditionType::DATA_TYPE_STRING, 'OWNewsletterEditionType' );
?>
