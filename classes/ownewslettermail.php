<?php

/**
 * Generate output and mail
 */
class OWNewsletterMail {

	/**
	 *
	 * @var string  CRLF - windows
	 */
	const HEADER_LINE_ENDING_CRLF = "\r\n";

	/**
	 *
	 * @var string  CR   - mac
	 */
	const HEADER_LINE_ENDING_CR = "\r";

	/**
	 *
	 * @var string  LF   - UNIX-MACOSX
	 */
	const HEADER_LINE_ENDING_LF = "\n";

	/**
	 *
	 * @var string
	 */
	protected $transportMethod = 'file';

	/**
	 *
	 * @var array assosiative array for additional email Header with some variables
	 *      for better bounce parsing
	 *      for example
	 *      array['X-OWNL-Edition']=lsjdfo13uru32s
	 */
	protected $ExtraEmailHeaderItemArray = array();

	/**
	 * which header line ending should be used for mail creation
	 * @var string
	 */
	protected $HeaderLineEnding = null;

	/**
	 * Mail info for sending
	 */
	protected $newsletterSending;
	protected $emailSender;
	protected $emailSenderName;
	protected $subject = '';
	protected $HTMLBody = '';
	protected $plainTextBody = '';
	protected $contentType = 'plain/text';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->setHeaderLineEndingFromIni();
		$this->resetExtraMailHeaders();
	}

	/**
	 * Send testnewsletter to one email address
	 *
	 * @param OWNewsletterSending $editonContentObjectVersion
	 * @param array $emailReceivers
	 * @return unknown_type
	 */
	function sendNewsletterTestMail( OWNewsletterSending $newsletterSending, $emailReceivers ) {
		// generate all newsletter versions
		$this->newsletterSending = $newsletterSending;
		$output = $this->newsletterSending->attribute( 'output' );
		$this->emailSender = trim( $this->newsletterSending->attribute( 'email_sender' ) );
		$this->emailSenderName = $this->newsletterSending->attribute( 'email_sender_name' );
		if ( isset( $output['subject'] ) ) {
			$this->subject = $output['subject'];
		}
		if ( isset( $output['body'] ) && isset( $output['body']['html'] ) ) {
			$this->HTMLBody = $output['body']['html'];
		}
		if ( isset( $output['body'] ) && isset( $output['body']['text'] ) ) {
			$this->plainTextBody = $output['body']['text'];
		}
		if ( isset( $output['content_type'] ) ) {
			$this->contentType = $output['content_type'];
		}
		$this->setTransportMethodPreviewFromIni();
		$sendResult = array();
		foreach ( $emailReceivers as $emailReceiver ) {
			$sendResult[] = $this->sendEmail( $emailReceiver );
		}
		return $sendResult;
	}

	/**
	 * Mainfunction for mail send
	 *
	 * @param unknown_type $emailReceiver
	 * @param boolean $isPreview
	 * @param string $emailCharset
	 * @return array
	 */
	public function sendEmail( $emailReceiver, $emailReceiverName = 'NL Test Receiver', $isPreview = false, $emailCharset = 'utf-8' ) {

		$transportMethod = $this->transportMethod;
		//$mail = new ezcMailComposer();
		$mail = new OWNewsletterMailComposer();
		$mail->charset = $emailCharset;
		$mail->subjectCharset = $emailCharset;
		// from and to addresses, and subject
		$mail->from = new ezcMailAddress( $this->emailSender, $this->emailSenderName );
		// returnpath for email bounces
		$mail->returnPath = new ezcMailAddress( $this->emailSender );

		$mail->addTo( new ezcMailAddress( trim( $emailReceiver ), $emailReceiverName ) );

		$mail->subject = $this->subject;
		if ( !empty( $this->HTMLBody ) ) {
			$mail->htmlText = $this->HTMLBody;
		}
		if ( !empty( $this->plainTextBody ) ) {
			$mail->plainText = $this->plainTextBody;
		}

		// http://ezcomponents.org/docs/api/latest/introduction_Mail.html#mta-qmail
		// HeaderLineEnding=auto
		// CRLF - windows - \r\n
		// CR - mac - \r
		// LF - UNIX-MACOSX - \n
		// default LF
		//ezcMailTools::setLineBreak( "\n" );
		ezcMailTools::setLineBreak( $this->HeaderLineEnding );

		// set 'x-ownl-' mailheader
		foreach ( $this->ExtraEmailHeaderItemArray as $key => $value ) {
			$mail->setHeader( $key, $value );
		}

		$mail->build();
		$transport = new OWNewsletterTransport( $transportMethod );
		$sendResult = $transport->send( $mail );
		$emailResult = array( 'send_result' => $sendResult,
			'email_sender' => $this->emailSender,
			'email_receiver' => $emailReceiver,
			'email_subject' => $this->subject,
			'email_charset' => $emailCharset,
			'transport_method' => $transportMethod );
		var_dump( $sendResult );
		die();
		if ( $sendResult ) {
			OWNewsletterLog::writeInfo( 'Email send ok', 'OWNewsletterMail', 'sendEmail', $emailResult );
		} else {
			OWNewsletterLog::writeError( 'Email send failed', 'OWNewsletterMail', 'sendEmail', $emailResult );
		}
		return $emailResult;
	}

	/**
	 * Read ini and set transport
	 *
	 * @return unknown_type
	 */
	function setTransportMethodPreviewFromIni() {
		$newsletterINI = eZINI::instance( 'newsletter.ini' );
		$transportMethodPreview = $newsletterINI->variable( 'NewsletterMailSettings', 'TransportMethodPreview' );
		$this->transportMethod = $transportMethodPreview;

		return $this->transportMethod;
	}

	/**
	 * Read ini and set transport
	 *
	 * @return unknown_type
	 */
	function setTransportMethodDirectlyFromIni() {
		$newsletterINI = eZINI::instance( 'newsletter.ini' );
		$transportMethodDirectly = $newsletterINI->variable( 'NewsletterMailSettings', 'TransportMethodDirectly' );
		$this->transportMethod = $transportMethodDirectly;

		return $this->transportMethod;
	}

	/**
	 * Read ini and set transport
	 *
	 * @return unknown_type
	 */
	function setTransportMethodCronjobFromIni() {
		$newsletterINI = eZINI::instance( 'newsletter.ini' );
		$transportMethodCronjob = $newsletterINI->variable( 'NewsletterMailSettings', 'TransportMethodCronjob' );
		$this->transportMethod = $transportMethodCronjob;
		return $this->transportMethod;
	}

	/**
	 * read header line ending settings from newsletter.ini
	 *     *
	 * http://ezcomponents.org/docs/api/latest/introduction_Mail.html#mta-qmail
	 * @return string headerlineending
	 */
	private function setHeaderLineEndingFromIni() {
		$newsletterINI = eZINI::instance( 'newsletter.ini' );
		$headerLineEndingIni = $newsletterINI->variable( 'NewsletterMailSettings', 'HeaderLineEnding' );

		switch ( $headerLineEndingIni ) {
			case 'CRLF':
				$this->HeaderLineEnding = self::HEADER_LINE_ENDING_CRLF;
				break;

			case 'CR':
				$this->HeaderLineEnding = self::HEADER_LINE_ENDING_CR;
				break;

			case 'LF':
				$this->HeaderLineEnding = self::HEADER_LINE_ENDING_LF;
				break;

			// TODO choose automatically the right settings
			case 'auto':
				$this->HeaderLineEnding = self::HEADER_LINE_ENDING_LF;
				break;

			// default line ending \n
			default:
				$this->HeaderLineEnding = self::HEADER_LINE_ENDING_LF;
				break;
		}

		return $this->HeaderLineEnding;
	}

	/**
	 * reset  $this->extraEmailHeaderItemArray and set version number
	 */
	public function resetExtraMailHeaders() {
		$this->extraEmailHeaderItemArray = array();
		$this->setExtraMailHeader( 'version', ownewsletterInfo::SOFTWARE_VERSION );
	}

	/**
	 * used by Newletter edition preview and newsletter cronjob process
	 *
	 * @param OWNewsletterUser $newsletterUser
	 * @return boolean
	 */
	public function setExtraMailHeadersByNewsletterUser( $newsletterUser ) {
		if ( $newsletterUser instanceof OWNewsletterUser ) {
			$this->setExtraMailHeader( 'receiver', $newsletterUser->attribute( 'email' ) );
			$this->setExtraMailHeader( 'user', $newsletterUser->attribute( 'hash' ) );
		} else {
			return false;
		}
	}

	/**
	 * used by newsletter cronjob process
	 *
	 * @param OWNewsletterUser $newsletterUser
	 * @return boolean
	 */
	public function setExtraMailHeadersByNewsletterSendItem( $newsletterEditionSendItem ) {
		if ( $newsletterEditionSendItem instanceof OWNewsletterEditionSendItem ) {
			// nl user header setzen
			$this->setExtraMailHeadersByNewsletterUser( $newsletterEditionSendItem->attribute( 'newsletter_user_object' ) );
			$this->setExtraMailHeader( 'senditem', $newsletterEditionSendItem->attribute( 'hash' ) );

			// unsubscribe hash
			$subscriptionObject = $newsletterEditionSendItem->attribute( 'newsletter_subscription_object' );
			$this->setExtraMailHeader( 'subscription', $subscriptionObject->attribute( 'hash' ) );
		} else {
			return false;
		}
	}

	/**
	 * Set a new extra mailheader item
	 *
	 * setExtraMailHeader( 'Version', '1.0.0' ) will add the following mail header item
	 *
	 * X-OWNl-Version : 1.0.0
	 *
	 * @param string $name
	 * @param string $value
	 * @return boolean
	 */
	public function setExtraMailHeader( $name, $value ) {
		$this->ExtraEmailHeaderItemArray['x-ownl-' . $name] = (string) $value;
		return true;
	}

}

?>