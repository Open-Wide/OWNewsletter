<?php

/**
 * Cronjob newsletter_mailqueue_process.php
 */
// Get all wait for process sending
$sendingList = OWNewsletterSending::fetchList( array( 'status' => array( array( OWNewsletterSending::STATUS_WAIT_FOR_PROCESS,
					OWNewsletterSending::STATUS_MAILQUEUE_PROCESS_STARTED ) ) ) );

// Create newsletterTracking object
$newsletterTracking = OWNewsletterTracking::create();

foreach ( $sendingList as $sending ) {
	// set startdate only at the first time
	if ( $sending->attribute( 'status' ) == OWNewsletterSending::STATUS_MAILQUEUE_CREATED ) {
		$sending->setAttribute( 'status', OWNewsletterSending::STATUS_MAILQUEUE_PROCESS_STARTED );
		$sending->store();
	}

	$newsletterIni = new eZINI( 'newsletter.ini' );
	$limit = $newsletterIni->variable( 'NewsletterMailSettings', 'EmailsBySendingSession' );

	$mail = new OWNewsletterMail();
	$mail->sendNewsletter( $sending, $limit, $newsletterTracking );

	$noSentItemCount = OWNewsletterSendingItem::countList( array(
				'edition_contentobject_id' => $sending->attribute( 'edition_contentobject_id' ),
				'status' => OWNewsletterSendingItem::STATUS_NEW 
			) );
	if( $noSentItemCount == 0 ) {
		$sending->setAttribute( 'status', OWNewsletterSending::STATUS_MAILQUEUE_PROCESS_FINISHED );
		$sending->store();
	}
}