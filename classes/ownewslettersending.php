<?php

class OWNewsletterSending extends eZPersistentObject {

    const STATUS_DRAFT = 0;
    const STATUS_WAIT_FOR_PROCESS = 1;
    const STATUS_MAILQUEUE_CREATED = 2;
    const STATUS_MAILQUEUE_PROCESS_STARTED = 3;
    const STATUS_MAILQUEUE_PROCESS_FINISHED = 4;
    const STATUS_ABORT = 9;

    /**
     * Contructor
     * 
     * @param array $row
     * @return void
     */
    function __construct( $row ) {
        $this->eZPersistentObject( $row );
    }

    /**
     * @return void
     */
    static function definition() {
        return array( 'fields' => array(
                'edition_contentobject_id' => array(
                    'name' => 'EditionContentObjectId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'edition_contentobject_version' => array(
                    'name' => 'EditionContentObjectVersion',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'newsletter_contentobject_id' => array(
                    'name' => 'NewsletterContentObjectId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'newsletter_contentobject_version' => array(
                    'name' => 'NewsletterContentObjectVersion',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'mailing_lists_string' => array(
                    'name' => 'MailingListsString',
                    'datatype' => 'string',
                    'default' => 'default',
                    'required' => true ),
                'siteaccess' => array(
                    'name' => 'SiteAccess',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true ),
                'creator_id' => array(
                    'name' => 'CreatorId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'created' => array(
                    'name' => 'Created',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'modified' => array(
                    'name' => 'Modified',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'waiting_for_process' => array(
                    'name' => 'WaitingForProcess',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false ),
                'mailqueue_created' => array(
                    'name' => 'MailQueueCreated',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false ),
                'mailqueue_process_started' => array(
                    'name' => 'MailQueueProcessStarted',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false ),
                'mailqueue_process_finished' => array(
                    'name' => 'MailQueueProcessFinished',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false ),
                'mailqueue_process_aborted' => array(
                    'name' => 'MailQueueProcessAborted',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false ),
                'status' => array(
                    'name' => 'Status',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true ),
                'serialized_output' => array(
                    'name' => 'SerializedOutput',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true ),
                'hash' => array(
                    'name' => 'Hash',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true ),
                'sender_email' => array(
                    'name' => 'SenderEmail',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true ),
                'sender_name' => array(
                    'name' => 'SenderName',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true ),
                'serialized_mail_personalizations' => array(
                    'name' => 'SerializedMailPersonalizations',
                    'datatype' => 'text',
                    'default' => 0,
                    'required' => false ),
                'send_date' => array(
                    'name' => 'SendDate',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false ),
            ),
            'keys' => array( 'edition_contentobject_id' ),
            'function_attributes' => array(
                'newsletter_object' => 'getNewsletterObject',
                'edition_object' => 'getEditionObject',
                'can_abort' => 'canAbort',
                'statistics' => 'getStatistics',
                'output' => 'getOutput',
                'mailing_lists_ids' => 'getMailingListIDs',
                'mail_personalizations' => 'unserializeMailPersonalizations',
            ),
            'class_name' => 'OWNewsletterSending',
            'name' => 'ownl_sending' );
    }

    /*     * **********************
     * FUNCTION ATTRIBUTES
     * ********************** */

    public function getNewsletterObject() {
        if( $this->attribute( 'status' ) == self::STATUS_DRAFT ) {
            return OWNewsletter::fetchLastVersion( $this->attribute( 'newsletter_contentobject_id' ) );
        }
        return OWNewsletter::fetchByCustomConditions( array(
                'contentobject_id' => $this->attribute( 'newsletter_contentobject_id' ),
                'contentobject_attribute_version' => $this->attribute( 'newsletter_contentobject_version' )
            ) );
    }

    public function getEditionObject() {
        if( $this->attribute( 'status' ) == self::STATUS_DRAFT ) {
            return OWNewsletterEdition::fetchLastVersion( $this->attribute( 'edition_contentobject_id' ) );
        }
        return OWNewsletterEdition::fetchByCustomConditions( array(
                'contentobject_id' => $this->attribute( 'edition_contentobject_id' ),
                'contentobject_attribute_version' => $this->attribute( 'edition_contentobject_version' )
            ) );
    }

    /**
     * Check if sending can be abort
     * 
     * @return boolean
     */
    public function canAbort() {
        return $this->attribute( 'status' ) != self::STATUS_MAILQUEUE_PROCESS_FINISHED || $this->attribute( 'status' ) != self::STATUS_ABORT;
    }

    /**
     * Statistic data about sending process
     *
     * @return array
     */
    public function getStatistics() {
        $editionContentobjectID = $this->attribute( 'edition_contentobject_id' );
        $itemsCount = OWNewsletterSendingItem::countList( array(
                'edition_contentobject_id' => $editionContentobjectID
            ) );
        $itemsNotSend = OWNewsletterSendingItem::countList( array(
                'edition_contentobject_id' => $editionContentobjectID,
                'status' => OWNewsletterSendingItem::STATUS_NEW
            ) );
        $itemsSend = OWNewsletterSendingItem::countList( array(
                'edition_contentobject_id' => $editionContentobjectID,
                'status' => OWNewsletterSendingItem::STATUS_SEND
            ) );
        $itemsBounced = OWNewsletterSendingItem::countList( array(
                'edition_contentobject_id' => $editionContentobjectID,
                'bounced' => array( '>', 0 )
            ) );
        $itemsSendInPersent = 0;
// catch division by zero
        if( $itemsCount > 0 ) {
            $itemsSendInPersent = round( $itemsSend / $itemsCount * 100, 1 );
        }

        return array(
            'items_count' => $itemsCount,
            'items_not_send' => $itemsNotSend,
            'items_send' => $itemsSend,
            'items_send_in_percent' => $itemsSendInPersent,
            'items_bounced' => $itemsBounced );
    }

    /**
     * Return unserialized output
     */
    public function getOutput() {
        return unserialize( $this->attribute( 'serialized_output' ) );
    }

    /**
     * Transform string to array for mailing_lists_ids attribute
     * 
     * @return array
     */
    public function getMailingListIDs() {
        return OWNewsletterUtils::stringToArray( $this->attribute( 'mailing_lists_string' ) );
    }

    /**
     * Unserialize serialized_mail_personalizations attribute
     * 
     * @return array
     */
    public function unserializeMailPersonalizations() {
        return unserialize( $this->attribute( 'serialized_mail_personalizations' ) );
    }

    /*     * **********************
     * FETCH METHODS
     * ********************** */

    /**
     * Return object by id
     *
     * @param integer $id
     * @return object
     */
    static function fetch( $id ) {
        $object = eZPersistentObject::fetchObject( self::definition(), null, array( 'edition_contentobject_id' => $id ), true );
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
        $limitArr = null;
        if( (int) $limit != 0 ) {
            $limitArr = array(
                'limit' => $limit,
                'offset' => $offset );
        }
        $objectList = eZPersistentObject::fetchObjectList( self::definition(), null, $conds, null, $limitArr, $asObject, null, null, null, null );
        return $objectList;
    }

    /**
     * Count all sendings with custom conditions
     *
     * @param array $conds
     * @return interger
     */
    static function countList( $conds = array() ) {
        $objectList = eZPersistentObject::count( self::definition(), $conds );
        return $objectList;
    }

    /*     * **********************
     * OBJECT METHODS
     * ********************** */

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
     */
    function setAttribute( $attr, $value ) {
        switch( $attr ) {
            case 'status':
                if( $value === self::STATUS_WAIT_FOR_PROCESS ) {
                    $this->setAttribute( 'waiting_for_process', time() );
                } elseif( $value === self::STATUS_MAILQUEUE_CREATED ) {
                    $this->setAttribute( 'mailqueue_created', time() );
                } elseif( $value === self::STATUS_MAILQUEUE_PROCESS_STARTED ) {
                    $this->setAttribute( 'mailqueue_process_started', time() );
                } elseif( $value === self::STATUS_MAILQUEUE_PROCESS_FINISHED ) {
                    $this->setAttribute( 'mailqueue_process_finished', time() );
                } elseif( $value === self::STATUS_ABORT ) {
                    $this->setAttribute( 'mailqueue_process_aborted', time() );
                }
                eZPersistentObject::setAttribute( $attr, $value );
            default:
                eZPersistentObject::setAttribute( $attr, $value );
        }
        eZContentCacheManager::clearContentCacheIfNeeded( array( $this->attribute( 'edition_contentobject_id' ) ) );
    }

    /**
     * reimplementation
     * update modified date
     *
     * @param unknown_type $fieldFilters
     * @return void
     */
    function store( $fieldFilters = null ) {
        $this->setAttribute( 'modified', time() );
        parent::storeObject( $this, $fieldFilters );
    }

    /**
     * Remove object and all its sending items
     * 
     * @param type $conditions
     * @param type $extraConditions
     */
    public function remove( $conditions = null, $extraConditions = null ) {
        parent::remove( $conditions, $extraConditions );
        $itemList = OWNewsletterSendingItem::fetchList( array(
                'edition_contentobject_id' => $this->attribute( 'edition_contentobject_id' )
            ) );
        foreach( $itemList as $item ) {
            $item->remove();
        }
    }

    /*     * **********************
     * PERSISTENT METHODS
     * ********************** */

    /**
     * Create new OWNewsletterSending object
     *
     * @param OWNewsletter $newsletter
     * @param OWNewsletterEdition $newsletterEdition
     * @return object
     */
    static function create( OWNewsletterEdition $newsletterEdition, $status = self::STATUS_DRAFT ) {
        $user = eZUser::currentUser();
        $creatorId = $user->attribute( 'contentobject_id' );
        $newsletter = $newsletterEdition->attribute( 'newsletter' );
        if( !$newsletter instanceof OWNewsletter ) {
            throw new OWNewsletterException( "Fail to find newsletter configuration" );
        }
        $mailingList = $newsletterEdition->attribute( 'mailing_lists_ids' );
        if( empty( $mailingList ) ) {
            throw new OWNewsletterException( "Mailing list is empty" );
        }
        $hashString = $newsletterEdition->attribute( 'mailing_lists_string' ) . $newsletterEdition->attribute( 'contentobject_id' ) . $newsletterEdition->attribute( 'contentobject_attribute_version' );

        $row = array(
            'edition_contentobject_id' => $newsletterEdition->attribute( 'contentobject_id' ),
            'edition_contentobject_version' => $newsletterEdition->attribute( 'contentobject_attribute_version' ),
            'newsletter_contentobject_id' => $newsletter->attribute( 'contentobject_id' ),
            'newsletter_contentobject_version' => $newsletter->attribute( 'contentobject_attribute_version' ),
            'mailing_lists_string' => $newsletterEdition->attribute( 'mailing_lists_string' ),
            'siteaccess' => $newsletter->attribute( 'main_siteaccess' ),
            'created' => time(),
            'creator_id' => $creatorId,
            'status' => $status,
            'hash' => OWNewsletterUtils::generateUniqueMd5Hash( $hashString ),
            'sender_email' => $newsletter->attribute( 'sender_email' ),
            'sender_name' => $newsletter->attribute( 'sender_name' ),
            'serialized_mail_personalizations' => $newsletter->attribute( 'serialized_mail_personalizations' )
        );
        $object = new OWNewsletterSending( $row );
        $object->setAttribute( 'serialized_output', $object->getSerializedOutput() );
        $object->store();
        return $object;
    }

    /**
     * Mark sending as wait for preocess if possible
     * 
     * @param OWNewsletterEdition $newsletterEdition
     */
    static function send( OWNewsletterEdition $newsletterEdition, $sendingDate = 0 ) {
        $editionContentObjectID = $newsletterEdition->attribute( 'contentobject_id' );
        $sendingObject = self::fetch( $editionContentObjectID );
        if( $sendingObject instanceof self && $sendingObject->attribute( 'status' ) == self::STATUS_DRAFT ) {
            $sendingObject = self::create( $newsletterEdition, self::STATUS_WAIT_FOR_PROCESS );
            $sendingObject->setAttribute( 'send_date', $sendingDate );
            $sendingObject->store();
        }
    }

    /**
     * Abort newsletter sending if possible
     * 
     * @param OWNewsletterEdition $newsletterEdition
     */
    static function abort( OWNewsletterEdition $newsletterEdition ) {
        $editionContentObjectID = $newsletterEdition->attribute( 'contentobject_id' );
        $sendingObject = self::fetch( $editionContentObjectID );
        if( $sendingObject instanceof self ) {
            if( $sendingObject->attribute( 'can_abort' ) ) {
                $sendingObject->setAttribute( 'status', self::STATUS_ABORT );
                $sendingObject->abortSendingItems();
                $sendingObject->store();
            }
        }
    }

    static function sendTest( OWNewsletterEdition $newsletterEdition, $testReceiverEmail ) {
        if( is_string( $testReceiverEmail ) ) {
            $testReceiverEmail = explode( ';', $testReceiverEmail );
        }
        $editionContentObjectID = $newsletterEdition->attribute( 'contentobject_id' );
        $sendingObject = self::fetch( $editionContentObjectID );
        if( $sendingObject instanceof self ) {
            $sendingObject = self::create( $newsletterEdition, $sendingObject->attribute( 'status' ) );
        } else {
            $sendingObject = self::create( $newsletterEdition );
        }
        $newsletterMail = new OWNewsletterMail();
        return $newsletterMail->sendNewsletterTestMail( $sendingObject, $testReceiverEmail );
    }

    /*     * **********************
     * OTHER METHODS
     * ********************** */

    protected function abortSendingItems() {
        /* TODO */
    }

    /**
     * Create a xml to save all rendered outputformats as a templatedraft so as
     * later to send several newsletters
     *
     * @return serialized array
     */
    public function getSerializedOutput() {
        $editionObject = $this->attribute( 'edition_object' );
        if( !$editionObject instanceof OWNewsletterEdition ) {
            throw new OWNewsletterException( "Fail to find newsletter edition configuration" );
        }
        $newsletterObject = $this->attribute( 'newsletter_object' );
        if( !$newsletterObject instanceof OWNewsletter ) {
            throw new OWNewsletterException( "Fail to find newsletter configuration" );
        }
        $mainSiteAccess = $newsletterObject->attribute( 'main_siteaccess' );
        $skinName = $newsletterObject->attribute( 'skin_name' );
        $output = $this->prepareOutput( $mainSiteAccess, $skinName );
        $output['skin_name'] = $skinName;
        return serialize( $output );
    }

    public function prepareOutput( $siteAccess, $skinName = 'default' ) {
        $editionContentObjectId = $this->attribute( 'edition_contentobject_id' );
        $versionId = $this->attribute( 'edition_contentobject_version' );
        if( $skinName == '' ) {
            $skinName = 'default';
        }

        $newsletterIni = eZINI::instance( 'newsletter.ini' );
        $phpCli = $newsletterIni->variable( 'NewsletterSettings', 'PhpCli' );

        $currentHostName = eZSys::hostname();
        $wwwDir = eZSys::wwwDir();

        $wwwDirString = '';
        if( $wwwDir != '' ) {
            $wwwDirString = "--www_dir=$wwwDir ";
        }

        $cmd = "\"$phpCli\" extension/ownewsletter/bin/php/createoutput.php --object_id=$editionContentObjectId --object_version=$versionId $wwwDirString--current_hostname=$currentHostName --skin_name=$skinName -s $siteAccess";

        $fileSep = eZSys::fileSeparator();
        $cmd = str_replace( '/', $fileSep, $cmd );

        eZDebug::writeDebug( "shell_exec( $cmd )", 'newsletter/preview' );

        $returnValue = shell_exec( escapeshellcmd( $cmd ) );
        $newsletterContentArray = unserialize( trim( $returnValue ) );

        if( $newsletterContentArray['html_mail_image_include'] === 1 ) {
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
        foreach( $body as $id => $value ) {
// replace all image src from http => file:\\ezroot\ so OWNewsletterMailComposer will embed it into the mail message
            if( $id === 'html' ) {
                $newsletterContentArrayNew['body'][$id] = str_replace( "src=\"$eZRoot", "src=\"$eZFile", $value );
            }
        }
        return $newsletterContentArrayNew;
    }

    /**
     * User personnalization if necessary (for Anonymous User)
     */

    public function applyAnonymousPersonnalizations() {

        $mailPersonalizations = $this->attribute( 'mail_personalizations' );

        $output = $this->attribute( 'output' );

        if( !empty( $mailPersonalizations ) ) {
            $newsletterINI = eZINI::instance( 'newsletter.ini' );

            foreach( $mailPersonalizations as $mailPersonalization ) {
                if( $newsletterINI->hasVariable( "$mailPersonalization-MailPersonalizationSettings", 'Class' ) ) {
                    $mailPersonalizationClass = $newsletterINI->variable( "$mailPersonalization-MailPersonalizationSettings", 'Class' );

                    if( is_callable( "$mailPersonalizationClass::applyOnSubjectAnonymous" ) ) {
                        $output['subject'] = call_user_func_array( "$mailPersonalizationClass::applyOnSubjectAnonymous", array( $output['subject'], $this ) );
                    }
                    if( is_callable( "$mailPersonalizationClass::applyOnHTMLBodyAnonymous" ) ) {
                        $output['body']['html'] = call_user_func_array( "$mailPersonalizationClass::applyOnHTMLBodyAnonymous", array( $output['body']['html'], $this ) );
                    }
                    if( is_callable( "$mailPersonalizationClass::applyOnPlainTextBodyAnonymous" ) ) {
                        $output['body']['text'] = call_user_func_array( "$mailPersonalizationClass::applyOnPlainTextBodyAnonymous", array( $output['body']['text'], $this ) );
                    }
                }
            }
        }
        return $output;
    }

    /**
     * User personnalization if necessary (for a subscriber)
     * @param OWNewsletterUser $newsletterUser
     */

    public function applySubscriberPersonnalizations($newsletterUser) {

        $mailPersonalizations = $this->attribute( 'mail_personalizations' );

        $output = $this->attribute( 'output' );

        if( !empty( $mailPersonalizations ) ) {
            $newsletterINI = eZINI::instance( 'newsletter.ini' );

            foreach( $mailPersonalizations as $mailPersonalization ) {
                if( $newsletterINI->hasVariable( "$mailPersonalization-MailPersonalizationSettings", 'Class' ) ) {
                    $mailPersonalizationClass = $newsletterINI->variable( "$mailPersonalization-MailPersonalizationSettings", 'Class' );
                    if( is_callable( "$mailPersonalizationClass::applyOnSubject" ) ) {
                        $output['subject'] = call_user_func_array( "$mailPersonalizationClass::applyOnSubject", array( $output['subject'], $newsletterUser, $this ) );
                    }
                    if( is_callable( "$mailPersonalizationClass::applyOnHTMLBody" ) ) {
                        $output['body']['html'] = call_user_func_array( "$mailPersonalizationClass::applyOnHTMLBody", array( $output['body']['html'], $newsletterUser, $this ) );
                    }
                    if( is_callable( "$mailPersonalizationClass::applyOnPlainTextBody" ) ) {
                        $output['body']['text'] = call_user_func_array( "$mailPersonalizationClass::applyOnPlainTextBody", array( $output['body']['text'], $newsletterUser, $this ) );
                    }
                }
            }
        }
        return $output;
    }

}
