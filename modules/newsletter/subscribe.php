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

$newsletterIni = eZINI::instance( 'newsletter.ini' );
$requiredFields = $newsletterIni->variable( 'NewsletterUserSettings', 'RequiredFields' );
$tpl->setVariable( 'required_fields', $requiredFields );

$newsletterUserRow = array(
	'email' => '',
	'salutation' => '',
	'first_name' => '',
	'last_name' => '',
	'status' => OWNewsletterUser::STATUS_PENDING,
	'subscription_list' => array(),
);
$template = 'design:newsletter/subscribe/form.tpl';

if ( $module->isCurrentAction( 'Subscribe' ) ) {
	if ( $module->hasActionParameter( 'NewsletterUser' ) ) {
		$newsletterUserRow = array_merge( $newsletterUserRow, $module->actionParameter( 'NewsletterUser' ) );

		$attributeWarningList = array();
		$warningList = array();
		foreach ( $requiredFields as $requiredField ) {
			if ( !isset( $newsletterUserRow[$requiredField] ) || empty( $newsletterUserRow[$requiredField] ) ) {
				$attributeWarningList[] = $requiredField;
				$warningList[] = 'Some fields are in error, please correct.';
			}
		}
		if ( empty( $newsletterUserRow['email'] ) ) {
			$attributeWarningList[] = 'email';
			$warningList[] = 'Some fields are in error, please correct.';
		}
		if ( empty( $newsletterUserRow['subscription_list'] ) ) {
			$attributeWarningList[] = 'subscription_list';
			$warningList[] = 'You must select at least one newsletter.';
		}
		$newsletterUser = OWNewsletterUser::fetchByEmail( $newsletterUserRow['email'] );
		if ( $newsletterUser instanceof OWNewsletterUser ) {
			$tpl->setVariable( 'existing_newsletter_user', $newsletterUser );
		} elseif ( empty( $warningList ) && !$newsletterUser ) {
			$newsletterUser = OWNewsletterUser::createOrUpdate( $newsletterUserRow, 'subscribe' );
			foreach ( $newsletterUserRow['subscription_list'] as $subscription ) {
				$newsletterUser->subscribeTo( $subscription, OWNewsletterSubscription::STATUS_PENDING, 'subscribe' );
			}
			$newsletterUser->sendConfirmationMail();
			$tpl->setVariable( 'existing_newsletter_user', $newsletterUser );
			$template = 'design:newsletter/subscribe/success.tpl';
		} else {
			$tpl->setVariable( 'attribute_warning_array', $attributeWarningList );
			$tpl->setVariable( 'warning_array', array_unique( $warningList ) );
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
