<?php

/**
 * Cronjob newsletter_mailqueue_create.php
 */
// Get all wait for process sending
$newsletterSendingWaitForProcessList = OWNewsletterSending::fetchList( array(
            'status' => OWNewsletterSending::STATUS_WAIT_FOR_PROCESS,
            'send_date' => array( '<=', time() )
        ) );

$newsletterINI = eZINI::instance( 'newsletter.ini' );
$developmentMode = false;
if ( $newsletterINI->hasVariable( 'NewsletterSettings', 'DevelopmentMode' ) && $newsletterINI->variable( 'NewsletterSettings', 'DevelopmentMode' ) == 'enabled' ) {
    $authorizedDomainNameList = $newsletterINI->hasVariable( 'NewsletterSettings', 'DevelopmentAuthorizedDomainName' ) ? $newsletterINI->variable( 'NewsletterSettings', 'DevelopmentAuthorizedDomainName' ) : array();
    $developmentMode = true;
}
foreach ( $newsletterSendingWaitForProcessList as $newsletterSending ) {
    $sendingTimestamp = $newsletterSending->attribute( 'send_date' );
    if ( $sendingTimestamp < time() ) {
        // Get all user with at least one approved subscription to on of the mailing list of the sending
        $mailingListsIDs = $newsletterSending->attribute( 'mailing_lists_ids' );
        if ( $developmentMode ) {
            $newsletterUserList = array();
            foreach ( $authorizedDomainNameList as $authorizedDomainName ) {
                $newsletterUserList = array_merge( $newsletterUserList, OWNewsletterUser::fetchListWithSubscription( array(
                            'email' => array( 'like', "%@$authorizedDomainName" ),
                            'status' => OWNewsletterUser::STATUS_CONFIRMED,
                            'subscription' => array(
                                'status' => OWNewsletterSubscription::STATUS_APPROVED,
                                'mailing_list_contentobject_id' => array( $mailingListsIDs ) )
                        ) ) );
            }
        } else {
            $newsletterUserList = OWNewsletterUser::fetchListWithSubscription( array(
                        'status' => OWNewsletterUser::STATUS_CONFIRMED,
                        'subscription' => array(
                            'status' => OWNewsletterSubscription::STATUS_APPROVED,
                            'mailing_list_contentobject_id' => array( $mailingListsIDs ) )
                    ) );
        }
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