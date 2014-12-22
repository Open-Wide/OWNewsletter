<?php

class OWNewsletterType extends eZDataType {

    const DATA_TYPE_STRING = 'ownewsletter';

    /**
     * Constructor
     *
     * @return void
     */
    function OWNewsletterType() {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'newsletter/datatypes', 'Newsletter', 'Datatype name' ), array(
            'serialize_supported' => true, 'translation_allowed' => false ) );
    }

    /**
     * Validates input on content object level
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#validateObjectAttributeHTTPInput($http, $base, $objectAttribute)
     * @return EZ_INPUT_VALIDATOR_STATE
     */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute ) {
        $validationErrorMesssageArray = array();
        $prefix = $base . '_ownewsletter_';
        $postfix = '_' . $contentObjectAttribute->attribute( 'id' );

        $mainSiteaccess = $http->postVariable( $prefix . 'MainSiteaccess' . $postfix );
        if( $mainSiteaccess == '' ) {
            $validationErrorMesssageArray[] = ezpI18n::tr( 'newsletter/datatype/ownewsletter', "Main Siteaccess must be set" );
        }

        $senderEmail = $http->postVariable( $prefix . 'SenderEmail' . $postfix );
        if( $senderEmail == '' || !eZMail::validate( $senderEmail ) ) {
            $validationErrorMesssageArray[] = ezpI18n::tr( 'newsletter/datatype/ownewsletter', "You have to set a valid sender email adress" );
        }

        $testReceiverEmail = $http->postVariable( $prefix . 'TestReceiverEmail' . $postfix );
        if( $testReceiverEmail == '' ) {
            $validationErrorMesssageArray[] = ezpI18n::tr( 'newsletter/datatype/ownewsletter', "You have to set a valid test receiver email" );
        } else {
            $receiverList = explode( ';', $testReceiverEmail );
            foreach( $receiverList as $receiver ) {
                if( eZMail::validate( $receiver ) == false ) {
                    $validationErrorMesssageArray[] = ezpI18n::tr( 'newsletter/datatype/ownewsletter', "You have to set a valid receiver email adress >> %email", null, array(
                            '%email' => $receiver ) );
                }
            }
        }

        if( count( $validationErrorMesssageArray ) == 0 ) {
            return eZInputValidator::STATE_ACCEPTED;
        } else {
            $validationErrorMessage = implode( '<br \>', $validationErrorMesssageArray );
            $contentObjectAttribute->setValidationError( $validationErrorMessage );
            return eZInputValidator::STATE_INVALID;
        }
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
        $prefix = $base . '_ownewsletter_';
        $postfix = '_' . $contentObjectAttribute->attribute( 'id' );

        $postListData = array();
        $postListData['default_mailing_lists_string'] = (array) $http->postVariable( $prefix . 'DefaultMailingListSelection' . $postfix );
        $postListData['main_siteaccess'] = $http->postVariable( $prefix . 'MainSiteaccess' . $postfix );
        $postListData['sender_email'] = $http->postVariable( $prefix . 'SenderEmail' . $postfix );
        $postListData['sender_name'] = $http->postVariable( $prefix . 'SenderName' . $postfix );
        $postListData['test_receiver_email'] = explode( ';', $http->postVariable( $prefix . 'TestReceiverEmail' . $postfix ) );
        $postListData['skin_name'] = $http->hasPostVariable( $prefix . 'SkinName' . $postfix ) ? $http->postVariable( $prefix . 'SkinName' . $postfix ) : '';
        $postListData['mail_personalizations'] = (array) $http->postVariable( $prefix . 'MailPersonalizations' . $postfix );

        $newsletterObject = new OWNewsletter( array(
            'contentobject_attribute_id' => $contentObjectAttribute->attribute( 'id' ),
            'contentobject_attribute_version' => $contentObjectAttribute->attribute( 'version' ),
            'contentobject_id' => $contentObjectAttribute->attribute( 'contentobject_id' ),
            'contentclass_id' => $contentclassAttribute->attribute( 'contentclass_id' ),
            'default_mailing_lists_string' => OWNewsletterUtils::arrayToString( $postListData['default_mailing_lists_string'] ),
            'main_siteaccess' => $postListData['main_siteaccess'],
            'sender_name' => $postListData['sender_name'],
            'sender_email' => $postListData['sender_email'],
            'test_receiver_email_string' => OWNewsletterUtils::arrayToString( $postListData['test_receiver_email'] ),
            'skin_name' => $postListData['skin_name'],
            'serialized_mail_personalizations' => serialize( $postListData['mail_personalizations'] )
            ) );
        $contentObjectAttribute->setContent( $newsletterObject );
        return true;
    }

    /**
     * Sets the default value
     *
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#initializeObjectAttribute($objectAttribute, $currentVersion, $originalContentObjectAttribute)
     */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute ) {
        if( $currentVersion != false ) {
            $data = $originalContentObjectAttribute->attribute( 'content' );

            if( $data instanceof OWNewsletter ) {
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

        $dataObject = OWNewsletter::fetch( $id, $version );
        if( !is_object( $dataObject ) ) {
            $dataObject = new OWNewsletter();
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
        return OWNewsletterType::objectAttributeContent( $contentObjectAttribute ) ? true : false;
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
        $newSiteAccessArray = $content->attribute( 'siteaccess_array' );
        foreach( $newSiteAccessArray as $index => $siteAccessName ) {
            if( $siteAccessName == $mainSiteAccess ) {
                $newSiteAccessArray[$index] = '[' . $siteAccessName . ']';
            }
        }

        $listTitle = $contentObjectAttribute->attribute( 'contentobject_id' )
            . '; ' . implode( ', ', $content->attribute( 'output_format_array' ) )
            . '; A' . $content->attribute( 'auto_approve_registered_user' )
            . '; P' . $content->attribute( 'mail_personalizations' )
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
        if( is_object( $object ) ) {
            $object->store();
            return true;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZDataType#deleteStoredObjectAttribute($objectAttribute, $version)
     */
    function deleteStoredObjectAttribute( $objectAttribute, $version = null ) {
        if( $version !== null ) {
            $object = OWNewsletter::fetch( $objectAttribute->attribute( "id" ), $version );
            if( $object instanceof OWNewsletterEdition ) {
                $object->remove();
            }
        } else {
            $objectList = OWNewsletter::fetchList( array(
                    'contentobject_attribute_id' => $objectAttribute->attribute( "id" )
                ) );
            foreach( $objectList as $object ) {
                $object->remove();
            }
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

        if( $this->Attributes["properties"]['object_serialize_map'] ) {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach( $map as $attributeName => $xmlName ) {
                if( $objectAttribute->hasAttribute( $attributeName ) ) {
                    $value = $objectAttribute->attribute( $attributeName );
                    $attributeNode = $dom->createElement( $xmlName, (string) $value );
                    $node->appendChild( $attributeNode );
                    unset( $attributeNode );
                } else {
                    eZDebug::writeError( "The attribute '$attributeName' does not exists for contentobject attribute " . $objectAttribute->attribute( 'id' ), 'eZDataType::serializeContentObjectAttribute' );
                }
            }
        } else {
            $newsletterContent = $objectAttribute->attribute( 'content' );
            $newsletterContentSerialized = serialize( $newsletterContent );
            $dataTextNode = $dom->createElement( 'ownewsletter' );
            $serializedNode = $dom->createCDATASection( $newsletterContentSerialized );
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

        if( $this->Attributes["properties"]['object_serialize_map'] ) {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach( $map as $attributeName => $xmlName ) {
                if( $objectAttribute->hasAttribute( $attributeName ) ) {
                    $elements = $attributeNode->getElementsByTagName( $xmlName );
                    if( $elements->length !== 0 ) {
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
            $oWNewsletterObjectSerialized = $attributeNode->getElementsByTagName( 'ownewsletter' )->item( 0 )->textContent;
            $oWNewsletterObject = unserialize( $oWNewsletterObjectSerialized );

            if( is_object( $oWNewsletterObject ) ) {
                $oWNewsletterObject->setAttribute( 'contentobject_attribute_id', $objectAttribute->attribute( 'id' ) );
                $oWNewsletterObject->setAttribute( 'contentobject_attribute_version', $objectAttribute->attribute( 'version' ) );
                $oWNewsletterObject->setAttribute( 'contentobject_id', $objectAttribute->attribute( 'contentobject_id' ) );
                $oWNewsletterObject->setAttribute( 'contentclass_id', $contentclassAttribute->attribute( 'contentclass_id' ) );
                $oWNewsletterObject->store();
                $objectAttribute->setAttribute( 'content', $oWNewsletterObject );
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

eZDataType::register( OWNewsletterType::DATA_TYPE_STRING, 'OWNewsletterType' );

