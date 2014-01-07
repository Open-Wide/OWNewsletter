<?php

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();
$template = "design:newsletter/mailbox/list.tpl";

/* Get views parameters */
$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( array(
	'offset' => 0,
	'status' => '' ), $userParameters );
$tpl->setVariable( 'view_parameters', $viewParameters );

/* Initilize module result */
$Result = array();
$Result['path'] = array(
	array(
		'url' => false,
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
	array(
		'url' => false,
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Mailboxes' ) ) );

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
	return eZModule::STATUS_REDIRECT;
}

/* Initilize mailbox row data */
$mailboxRow = array(
	'email' => '',
	'server' => '',
	'port' => '',
	'username' => '',
	'password' => '',
	'type' => '',
	'is_activated' => true,
	'is_ssl' => true,
	'delete_mails_from_server' => true
);
if ( $Params['mailboxID'] && is_numeric( $Params['mailboxID'] ) ) {
	$mailbox = OWNewsletterMailbox::fetch( $Params['mailboxID'] );
	if ( $mailbox instanceof OWNewsletterMailbox ) {
		$mailboxRow['id'] = $Params['mailboxID'];
		$mailboxRow['last_server_connect'] = $mailbox->attribute( 'last_server_connect' );
	} else {
		unset( $mailbox );
	}
}

/* If submit a newsletter user form (new or edit) */
if ( $module->hasActionParameter( 'Mailbox' ) ) {
	$mailboxData = $module->actionParameter( 'Mailbox' );
	foreach ( $mailboxData as $data => $value ) {
		switch ( $data ) {
			case 'is_activated':
			case 'is_ssl':
			case 'delete_mails_from_server' :
				$mailboxRow[$data] = (boolean) $value;
				break;
			default :
				$mailboxRow[$data] = trim( $value );
				break;
		}
	}
	try {
		$mailboxObject = OWNewsletterMailbox::createOrUpdate( $mailboxRow );
		$module->redirectTo( $redirectUrlSuccess );
		return eZModule::STATUS_REDIRECT;
	} catch ( Exception $e ) {
		$error = $e->getMessage();
		$tpl->setVariable( 'warning_array', array( $error ) );
	}
}

if ( $module->isCurrentAction( 'SubmitMailbox' ) ) {
	if ( isset( $mailbox ) ) {
		$tpl->setVariable( 'mailbox', $mailbox );
	} else {
		$tpl->setVariable( 'mailbox', $mailboxRow );
	}
	$template = "design:newsletter/mailbox/form.tpl";
} elseif ( $module->isCurrentAction( 'DisableMailbox' ) ) {
	if ( isset( $mailbox ) ) {
		$mailbox->setAttribute( 'is_activated', false );
		$mailbox->store();
		$module->redirectTo( $redirectUrlSuccess );
		return eZModule::STATUS_REDIRECT;
	} else {
		$tpl->setVariable( 'warning_array', array( 'Mailbox not found' ) );
	}
} elseif ( $module->isCurrentAction( 'EnableMailbox' ) ) {
	if ( isset( $mailbox ) ) {
		$mailbox->setAttribute( 'is_activated', true );
		$mailbox->store();
		$module->redirectTo( $redirectUrlSuccess );
		return eZModule::STATUS_REDIRECT;
	} else {
		$tpl->setVariable( 'warning_array', array( 'Mailbox not found' ) );
	}
} elseif ( $module->isCurrentAction( 'RemoveMailbox' ) ) {
	if ( isset( $mailbox ) ) {
		$mailbox->remove();
		$module->redirectTo( 'newsletter/mailbox' );
		return eZModule::STATUS_REDIRECT;
	} else {
		$tpl->setVariable( 'warning_array', array( 'Mailbox not found' ) );
	}
} else {
	$conds = array();
	if ( $viewParameters['status'] == 'active' ) {
		$conds['is_activated'] = true;
	} elseif ( $viewParameters['status'] == 'inactive' ) {
		$conds['is_activated'] = false;
	}
	$tpl->setVariable( 'mailbox_list', OWNewsletterMailbox::fetchList( $conds ) );
	$tpl->setVariable( 'all_mailbox_list_count', OWNewsletterMailbox::countList() );
	$tpl->setVariable( 'mailbox_list_count', OWNewsletterMailbox::countList( $conds ) );
}
$Result['content'] = $tpl->fetch( $template );

