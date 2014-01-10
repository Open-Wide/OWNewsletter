<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();

/* Retrieval of cancel and success redirect URLs */
$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/subscribe';
if ( $module->hasActionParameter( 'RedirectUrlActionCancel' ) ) {
	$redirectUrlCancel = $module->actionParameter( 'RedirectUrlActionCancel' );
}
if ( $module->hasActionParameter( 'RedirectUrlActionSuccess' ) ) {
	$redirectUrlSuccess = $module->actionParameter( 'RedirectUrlActionSuccess' );
}
$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_success', $redirectUrlSuccess );

/* If press Cancel button */
if ( $module->isCurrentAction( 'Cancel' ) ) {
	$module->redirectTo( $redirectUrlCancel );
}

$newsletterUserRow = array(
	'email' => '',
	'salutation' => '',
	'first_name' => '',
	'last_name' => '',
	'status' => OWNewsletterUser::STATUS_PENDING,
	'subscription_list' => array(),
);
$template = 'design:newsletter/subscribe/confirmation.tpl';

if ( $module->isCurrentAction( 'Subscribe' ) ) {
	if ( $module->hasActionParameter( 'NewsletterUser' ) ) {
		$newsletterUserRow = array_merge( $newsletterUserRow, $module->actionParameter( 'NewsletterUser' ) );
		$warningMessages = array();
		if ( empty( $newsletterUserRow['email'] ) ) {
			$warningMessages[] = array(
				'field_key' => ezpI18n::tr( 'newsletter/subscribe', 'E-mail' ),
				'message' => ezpI18n::tr( 'newsletter/warning_message', 'You must provide a valid email address.' ) );
		}
		if ( empty( $newsletterUserRow['subscription_list'] ) ) {
			$warningMessages[] = array(
				'field_key' => ezpI18n::tr( 'newsletter/subscribe', 'Newsletter' ),
				'message' => ezpI18n::tr( 'newsletter/warning_message', 'You must select at least one newsletter.' ) );
		}
		$newsletterUser = OWNewsletterUser::fetchByEmail( $newsletterUserRow['email'] );
		if( $newsletterUser instanceof OWNewsletterUser ) {
			$tpl->setVariable( 'existing_newsletter_user', $newsletterUser );
		} elseif ( empty( $warningMessages ) && !$newsletterUser ) {
			$newsletterUser = OWNewsletterUser::createOrUpdate( $newsletterUserRow, 'subscribe' );
			foreach ( $newsletterUserRow['subscription_list'] as $subscription ) {
				$newsletterUser->subscribeTo( $subscription, OWNewsletterSubscription::STATUS_PENDING, 'subscribe' );
			}
			$newsletterUser->sendSubscriptionConfirmationMail( );
			$tpl->setVariable( 'existing_newsletter_user', $newsletterUser );
			$template = 'design:newsletter/subscribe/success.tpl';
		} else {
			$tpl->setVariable( 'warning_array', $warningMessages );
		}
	}
}
$tpl->setVariable( 'newsletter_user', $newsletterUserRow );
$tpl->setVariable( 'available_salutation_array', OWNewsletterUser::getAvailablesSalutationsFromIni() );

$Result = array();
$Result['content'] = $tpl->fetch( $template );
$Result['path'] = array(
	array(
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
	array(
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Subscribe' ) ) );
