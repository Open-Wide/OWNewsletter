<?php

/**
 * Cronjob newsletter_mailqueue_create.php
 */

$logInstance = OWNewsletterLog::getInstance( true );
// Get all wait for process sending
$newsletterSendingWaitForProcessList = OWNewsletterSending::fetchList( array( 'status' => OWNewsletterSending::STATUS_WAIT_FOR_PROCESS ) );
foreach ( $newsletterSendingWaitForProcessList as $newsletterSending ) {
	$sendingTimestamp = $newsletterSending->attribute( 'send_date' );
	if ( $sendingTimestamp < time() ) {
		// Get all user with at least one approved subscription to on of the mailing list of the sending
		$mailingListsIDs = $newsletterSending->attribute( 'mailing_lists_ids' );
		$newsletterUserList = OWNewsletterUser::fetchListWithSubsricption( array(
					'subscription' => array(
						'status' => OWNewsletterSubscription::STATUS_APPROVED,
						'mailing_list_contentobject_id' => array( $mailingListsIDs ) )
				) );
		// create the mailing list
		foreach ( $newsletterUserList as $counter => $newsletterUser ) {
			$newsletterSending->sync();
			if ( $newsletterSending->attribute( 'status' ) == OWNewsletterSending::STATUS_WAIT_FOR_PROCESS ) {
				$newSendingItem = OWNewsletterSendingItem::create( $newsletterSending, $newsletterUser );
			}
		}
		$newsletterSending->setAttribute( 'status', OWNewsletterSending::STATUS_MAILQUEUE_CREATED );
		$newsletterSending->store();
	}
}