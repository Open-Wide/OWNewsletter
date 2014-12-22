<?php

$ini = eZINI::instance( 'newsletter.ini' );
$ConfirmationTimeOut = $ini->variable( 'NewsletterSettings', 'ConfirmationTimeOut' );
eval( '$ConfirmationTimeOut = ' . $ConfirmationTimeOut . ';' );

$pendingUserList = OWNewsletterUser::fetchList( array( 'status' => OwNewsletterUser::STATUS_PENDING ) );
foreach( $pendingUserList as $pendingUser ) {
    if( ($pendingUser->Created + $ConfirmationTimeOut) < time() ) {
        $pendingUser->remove();
    }
}
