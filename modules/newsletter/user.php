<?php

/**
 * newsletter/user => list users / new user
 * newsletter/user/<newsletterUserID> => show user / edit user / delete user
 * newsletter/user/<newsletterUserID>/<mailingListContentobjectID> => show subscription / approve subscription
 */
$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();

/* Get views parameters */
$viewParameters = array( 'status' => FALSE, 'offset' => 0 );
if ( is_array( $Params['UserParameters'] ) ) {
	$viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}
$tpl->setVariable( 'view_parameters', $viewParameters );

/* Initilize module result */
$Result = array();
$Result['path'] = array(
	array(
		'url' => 'newsletter/index',
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
	array(
		'url' => 'newsletter/user',
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Users' ) ) );

/* Retrieval of cancal and success redirect URLs */
$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/user';
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

/* Initilize newsletter user row data */
$newsletterUserRow = array(
	'first_name' => '',
	'last_name' => '',
	'organisation' => '',
	'email' => '',
	'salutation' => '',
	'note' => '',
	'status' => OWNewsletterUser::STATUS_CONFIRMED,
	'id_array' => array(),
	'mailing_list_array' => array()
);
if ( $Params['newsletterUserID'] && is_numeric( $Params['newsletterUserID'] ) ) {
	$newsletterUser = OWNewsletterUser::fetch( $Params['newsletterUserID'] );
	$newsletterUserRow['id'] = $Params['newsletterUserID'];
	if ( $Params['mailingListContentobjectID'] && is_numeric( $Params['mailingListContentobjectID'] ) ) {
		$subscription = OWNewsletterSubscription::fetch( $Params['newsletterUserID'], $Params['mailingListContentobjectID'] );
	}
}

/* If submit a newsletter user form (new or edit) */
if ( $module->hasActionParameter( 'NewsletterUser' ) ) {
	$newsletterUserData = $module->actionParameter( 'NewsletterUser' );
	foreach ( $newsletterUserData as $data => $value ) {
		switch ( $data ) {
			case 'first_name':
			case 'last_name':
			case 'organisation' :
			case 'email' :
			case 'salutation' :
				$newsletterUserRow[$data] = trim( $value );
				break;
			case 'note' :
			case 'subscription_list':
				$newsletterUserRow[$data] = $value;
				break;
		}
	}
	try {
		$newsletterUserObject = OWNewsletterUser::createOrUpdate( $newsletterUserRow, 'user_edit' );
		$newsletterUserObject->updateSubscriptionList( $newsletterUserRow['subscription_list'], 'user_edit' );
	} catch ( Exception $e ) {
		$error = $e->getMessage();
	}
	if ( $newsletterUserObject instanceof OWNewsletterUser ) {
		$tpl->setVariable( 'subscription_array', $newsletterUserObject->attribute( 'subscription_array' ) );
	}
	if ( isset( $error ) ) {
		$tpl->setVariable( 'warning_array', array( $error ) );
	} else {
		$module->redirectTo( $redirectUrlSuccess );
	}
}

if ( $module->isCurrentAction( 'SubscribeNewsletterUser' ) ) {
	if ( $module->hasActionParameter( 'Email' ) ) {
		$email = $module->actionParameter( 'Email' );
	}
	if ( $module->hasActionParameter( 'MailingListContentObjectID' ) ) {
		$mailingListContentObjectID = $module->actionParameter( 'MailingListContentObjectID' );
	}
	if ( $email && $mailingListContentObjectID ) {
		$newsletterUser = OWNewsletterUser::fetchByEmail( $email );
		if ( $newsletterUser instanceof OWNewsletterUser ) {
			$newsletterUser->subscribeTo( $mailingListContentObjectID, OWNewsletterSubscription::STATUS_APPROVED, 'user_edit' );
		} else {
			$newsletterUserRow['email'] = $email;
			$newsletterUser = OWNewsletterUser::createOrUpdate( $newsletterUserRow, 'user_edit' );
			$newsletterUser->subscribeTo( $mailingListContentObjectID, OWNewsletterSubscription::STATUS_APPROVED, 'user_edit' );
		}
		$module->redirectTo( $redirectUrlSuccess );
	}
} elseif ( $module->isCurrentAction( 'SubmitNewsletterUser' ) ) { /* If press SubmitNewsletterUser button to access or validate form */
	if ( isset( $newsletterUser ) ) { /* edit user */
		$tpl->setVariable( 'newsletter_user', $newsletterUser );
		$tpl->setVariable( 'subscription_array', $newsletterUser->attribute( 'subscription_array' ) );
		$Result['path'][] = array( 'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Edit' ) );
	} else { /* new user */
		$tpl->setVariable( 'newsletter_user', $newsletterUserRow );
		$Result['path'][] = array( 'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'New' ) );
	}
	$tpl->setVariable( 'available_salutation_array', OWNewsletterUser::getAvailablesSalutationsFromIni() );
	$Result['content'] = $tpl->fetch( 'design:newsletter/user/form.tpl' );
} elseif ( isset( $newsletterUser ) ) { /* show user */
	$tpl->setVariable( 'newsletter_user', $newsletterUser );
	$Result['path'][] = array(
		'url' => 'newsletter/user/' . $newsletterUser->attribute( 'id' ),
		'text' => $newsletterUser->attribute( 'name' )
	);
	if ( isset( $subscription ) ) {
		if ( $module->isCurrentAction( 'ApproveSubscription' ) ) { /* approve user subscription */
			$subscription->approve();
			$module->redirectTo( $redirectUrlSuccess );
		} elseif ( $module->isCurrentAction( 'RemoveSubscription' ) ) { /* approve user subscription */
			$subscription->removeByAdmin();
			$module->redirectTo( $redirectUrlSuccess );
		} else { /* show user subscription */
			$tpl->setVariable( 'subscription', $subscription );
			$Result['path'][] = array( 'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Subscription' ) );
			$Result['content'] = $tpl->fetch( 'design:newsletter/user/subscription.tpl' );
		}
	} elseif ( $module->isCurrentAction( 'RemoveNewsletterUser' ) ) {
		$newsletterUser->setRemoved( true );
		$module->redirectTo( $redirectUrlSuccess );
	} elseif ( $module->isCurrentAction( 'RemoveForGoodNewsletterUser' ) ) {
		$newsletterUser->remove();
		$redirectUrlRemove = 'newsletter/user';
		if ( $module->hasActionParameter( 'RedirectUrlActionRemove' ) ) {
			$redirectUrlRemove = $module->actionParameter( 'RedirectUrlActionRemove' );
		}
		$module->redirectTo( $redirectUrlRemove );
	} elseif ( $module->isCurrentAction( 'ConfirmNewsletterUser' ) ) {
		$newsletterUser->confirm();
		$module->redirectTo( $redirectUrlSuccess );
	} else {
		$Result['content'] = $tpl->fetch( 'design:newsletter/user/show.tpl' );
	}
} else { /* all other case : list users */
	$Result['content'] = $tpl->fetch( 'design:newsletter/user/list.tpl' );
}




