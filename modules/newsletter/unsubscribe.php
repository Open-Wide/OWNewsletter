<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();

$newsletterUser = OWNewsletterUser::fetchByHash( $Params['Hash'] );

if ( !$newsletterUser instanceof OWNewsletterUser || $newsletterUser->isOnBlacklist() ) {
	return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$activeSubscriptions = $newsletterUser->attribute( 'active_subscriptions' );
if ( count( $activeSubscriptions ) == 0 ) {
	$template = 'design:newsletter/unsubscribe/already_done.tpl';
} elseif ( $module->isCurrentAction( 'Unsubscribe' ) ) {
	$newsletterUser->unsubscribe();
	$template = 'design:newsletter/unsubscribe/success.tpl';
} else if ( $module->isCurrentAction( 'Cancel' ) ) {
	$module->redirectTo( '/' );
} else {
	$template = 'design:newsletter/unsubscribe/confirmation.tpl';
}

$tpl->setVariable( 'newsletter_user', $newsletterUser );

$Result = array();
$Result['content'] = $tpl->fetch( $template );
$Result['path'] = array(
	array(
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
	array(
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Unsubscribe' ) ) );
