<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();

$newsletterUser = OWNewsletterUser::fetchByHash( $Params['Hash'] );

if( !$newsletterUser instanceof OWNewsletterUser || $newsletterUser->isOnBlacklist() ) {
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

switch( $newsletterUser->attribute( 'status' ) ) {
    case OWNewsletterUser::STATUS_PENDING:
        $newsletterUser->setConfirmed();
        $messageActivated = true;
        break;
    default:
        $messageActivated = false;
}

// On affiche la page avec le bon message

$tpl->setVariable( 'newsletter_user', $newsletterUser );
$tpl->setVariable( 'message_activated', $messageActivated );


$Result = array();
$Result['content'] = $tpl->fetch( 'design:newsletter/confirmation/success.tpl' );
