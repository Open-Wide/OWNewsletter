<?php

class OWNewsletterBounce extends eZPersistentObject {

	/**
	 * store content string of mailobject
	 *
	 * @var string
	 */
	var $MessageString = null;

	/**
	 * constructor
	 *
	 * @param unknown_type $row
	 * @return void
	 */
	function __construct( $row ) {
		$this->eZPersistentObject( $row );
	}

	/**
	 * data fields ....
	 *
	 * @return void
	 */
	static function definition() {
		return array( 'fields' => array(
				'id' => array(
					'name' => 'ID',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'mailbox_id' => array(
					'name' => 'MailboxId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'message_id' => array(
					'name' => 'MessageId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'message_identifier' => array(
					'name' => 'MessageIdentifier',
					'datatype' => 'string',
					'default' => '',
					'required' => true ),
				'message_size' => array(
					'name' => 'MessageSize',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'created' => array(
					'name' => 'Created',
					'datatype' => 'integer',
					'default' => 0,
					'required' => true ),
				'processed' => array(
					'name' => 'Processed',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'newsletter_user_id' => array(
					'name' => 'NewsletterUserId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'edition_contentobject_id' => array(
					'name' => 'EditionContentObjectId',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ),
				'bounce_code' => array(
					'name' => 'BounceCode',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'email_from' => array(
					'name' => 'EmailFrom',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'email_to' => array(
					'name' => 'EmailTo',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'email_subject' => array(
					'name' => 'EmailSubject',
					'datatype' => 'string',
					'default' => '',
					'required' => false ),
				'sending_date' => array(
					'name' => 'EmailSendDate',
					'datatype' => 'integer',
					'default' => 0,
					'required' => false ) ),
			'keys' => array( 'id' ),
			'increment_key' => 'id',
			'class_name' => 'OWNewsletterBounce',
			'name' => 'ownl_bounce',
			'function_attributes' => array(
				'is_bounce' => 'isBounce',
			),
		);
	}

	/*	 * **********************
	 * FUNCTION ATTRIBUTES
	 * ********************** */

	/**
	 * if bounce_code is a bounce
	 *
	 * @return boolean
	 */
	public function isBounce() {
		$bounceCode = $this->attribute( 'bounce_code' );
		if ( $bounceCode === 0 ||
				$bounceCode === '' ||
				$bounceCode === '0' ||
				$bounceCode === null ) {
			return false;
		} else {
			return true;
		}
	}

	/*	 * **********************
	 * FETCH METHODS
	 * ********************** */

	/**
	 * Return object by id
	 *
	 * @param integer $id
	 * @return object or boolean
	 */
	static public function fetch( $id ) {
		$object = eZPersistentObject::fetchObject( self::definition(), null, array( 'id' => $id ), true );
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
		$sortArr = array(
			'created' => 'desc' );
		$limitArr = null;

		if ( (int) $limit != 0 ) {
			$limitArr = array(
				'limit' => $limit,
				'offset' => $offset );
		}
		$objectList = eZPersistentObject::fetchObjectList( self::definition(), null, $conds, $sortArr, $limitArr, $asObject, null, null, null, null );
		return $objectList;
	}

	/**
	 * Count all subsciptions with custom conditions
	 *
	 * @param array $conds
	 * @return interger
	 */
	static function countList( $conds = array() ) {
		$objectList = eZPersistentObject::count( self::definition(), $conds );
		return $objectList;
	}

	/*	 * **********************
	 * OBJECT METHODS
	 * ********************** */

	/*	 * **********************
	 * PERSISTENT METHODS
	 * ********************** */

	/**
	 * adding an mailobject fetched from imap or pop3
	 * it will store it to db and local filesystem
	 *
	 * @todo parse created from mail header
	 * @param integer $mailboxId
	 * @param integer $messageId
	 * @param string $messageString
	 * @return object / false if not create
	 */
	public static function addItem( $mailboxId, $messageIdentifier, $messageId, $messageString ) {
		$foundMessage = self::fetchList( array(
					'mailbox_id' => $mailboxId,
					'message_identifier' => $messageIdentifier
				) );

		if ( !$foundMessage instanceof self ) {
			// object with fetch id not exists, than start the store progress
			$row = array( 'created' => time(),
				'mailbox_id' => $mailboxId,
				'message_id' => $messageId,
				'message_identifier' => $messageIdentifier,
				'message_size' => strlen( $messageString )
			);

			$object = new self( $row );
			$object->store();

			$object->MessageString = $messageString;

			// store message on filesystem
			$object->storeMessageToFilesystem();

			return $object;
		} else {
			return false;
		}
	}

	/*	 * **********************
	 * OTHER METHODS
	 * ********************** */

	/**
	 * parse all mailitems of all active mailboxes and get status code ...
	 *
	 * @return array
	 */
	public static function parseActiveItems() {
		// fetch mails which has a empty progressed_field ( unparsed mails )
		$objectList = self::fetchList( array( 'processed' => false ) );
		$parseResultArray = array();
		foreach ( $objectList as $item ) {
			$parseResultArray[$item->attribute( 'id' )] = $item->parseMail();
		}
		return $parseResultArray;
	}

	/**
	 * store mailitem on filesystem
	 *
	 * @todo eZFile::create result
	 * @return void
	 */
	protected function storeMessageToFilesystem() {
		$filePathArray = $this->getFilePathArray();
		$messageData = $this->MessageString;
		eZFile::create( $filePathArray['file_name'], $filePathArray['file_dir'], $messageData );
	}

	/**
	 * create the dir and filename for the current mailboxItem
	 *
	 * @return array array( 'file_path' => $filePath,
	 *                 'file_dir'  => $dir,
	 *                 'file_name' => $fileName )
	 */
	public function getFilePathArray() {
		$itemId = $this->attribute( 'id' );
		$mailboxId = $this->attribute( 'mailbox_id' );
		$createTimestamp = $this->attribute( 'created' );

		$varDir = eZSys::varDirectory();
		$year = date( 'Y', $createTimestamp );
		$month = date( 'm', $createTimestamp );
		$day = date( 'd', $createTimestamp );

		// $dir = $varDir . "/newsletter/mailbox/$mailboxId/$year/$month/$day/";
		$dir = eZDir::path( array(
					$varDir,
					'newsletter',
					'mailbox',
					$mailboxId,
					$year,
					$month,
					$day
				) );

		$fileName = "$mailboxId-$year$month$day-$itemId.mail";
		$fileSep = eZSys::fileSeparator();
		$filePath = $dir . $fileSep . $fileName;
		return array(
			'file_path' => $filePath,
			'file_dir' => $dir,
			'file_name' => $fileName
		);
	}

	/**
	 * parse mail
	 *
	 * @return array
	 */
	public function parseMail() {
		$mailParserObject = new OWNewsletterMailParser( $this );
		$parseResult = $mailParserObject->parse();
		$this->saveParsedInfos( $parseResult );
		return $parseResult;
	}

	/**
	 * saved parsed infos from ezcMailObject into database
	 *
	 * @param $parsedResult
	 */
	private function saveParsedInfos( $parsedResult ) {
		$this->setAttribute( 'email_from', $parsedResult['from'] );
		$this->setAttribute( 'email_to', $parsedResult['to'] );
		$this->setAttribute( 'email_subject', $parsedResult['subject'] );
		$this->setAttribute( 'bounce_code', $parsedResult['error_code'] );
		$this->setAttribute( 'sending_date', $this->convertEmailSendDateToTimestamp( $parsedResult['sending_date'] ) );
		
		// if x-ownl-senditem hash was set in bounce mail than fetch some ez data
		if ( isset( $parsedResult['x-ownl-senditem'] ) ) {
			$sendItemHash = $parsedResult['x-ownl-senditem'];

			// try to fetch edition send item object
			$sendItemObject = OWNewsletterSendingItem::fetchByHash( $sendItemHash, true );

			if ( is_object( $sendItemObject ) ) {
				$newsletterUserId = $sendItemObject->attribute( 'newsletter_user_id' );
				$editionSendId = $sendItemObject->attribute( 'edition_contentobject_id' );

				$this->setAttribute( 'newsletter_user_id', $newsletterUserId );
				$this->setAttribute( 'edition_contentobject_id', $editionSendId );

				if ( $this->isBounce() ) {
					$sendItemObject->setBounced();
					$newsletterUser = $sendItemObject->attribute( 'newsletter_user' );

					if ( is_object( $newsletterUser ) ) {
						// bounce nl user
						$isHardBounce = false;
						$newsletterUser->setBounced( $isHardBounce );
					}
				}
			}
		}
		// if only set 'x-ownl-user'
		elseif ( isset( $parsedResult['x-ownl-user'] ) ) {
			$newsletterUser = OWNewsletterUser::fetchByHash( $sendItemHash, true );

			if ( is_object( $sendItemObject ) ) {
				$newsletterUserId = $newsletterUser->attribute( 'id' );
				$this->setAttribute( 'newsletter_user_id', $newsletterUserId );

				if ( $this->isBounce() ) {
					// bounce nl user
					$isHardBounce = false;
					$newsletterUser->setBounced( $isHardBounce );
				}
			}
		}

		// item is parsed
		$this->setAttribute( 'processed', time() );
		$this->store();
	}

	/**
	 * convert given string to timestamp
	 *
	 * format: Tue, 27 Oct 2009 15:27:35 +0100 => timestamp
	 *
	 * @param string $emailSendDate
	 * @return timestamp
	 */
	protected function convertEmailSendDateToTimestamp( $emailSendDate ) {
		return strtotime( $emailSendDate );
	}

	/**
	 * try to read the raq mailmessage from local filesystem
	 *
	 * @return string mailmessage or false
	 */
	public function getRawMailMessageContent( $asArray = false ) {
		$filePath = $this->getFilePath();
		if ( file_exists( $filePath ) ) {
			if ( $asArray === false ) {
				return file_get_contents( $filePath );
			} else {
				return file( $filePath );
			}
		} else {
			return false;
		}
	}

	/**
	 * get file path
	 *
	 * @return string path to the current mailfile
	 */
	public function getFilePath() {
		$filePathArray = $this->getFilePathArray();
		return $filePathArray['file_path'];
	}

}
