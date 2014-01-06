<?php

$Module = array(
	"name" => "OW Newsletter" );

$ViewList = array();

$ViewList['index'] = array(
	'script' => 'index.php',
	'functions' => array( 'index' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array() );

$ViewList['user'] = array(
	'script' => 'user.php',
	'functions' => array( 'manage_user' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'ui_context' => 'navigation',
	'params' => array( 'newsletterUserID', 'mailingListContentobjectID' ),
	'single_post_actions' => array(
		'CancelButton' => 'Cancel',
		'SubmitNewsletterUserButton' => 'SubmitNewsletterUser',
		'SubscribeNewsletterUserButton' => 'SubscribeNewsletterUser',
		'ConfirmNewsletterUserButton' => 'ConfirmNewsletterUser',
		'RemoveNewsletterUserButton' => 'RemoveNewsletterUser',
		'RemoveForGoodNewsletterUserButton' => 'RemoveForGoodNewsletterUser',
		'ApproveSubscriptionButton' => 'ApproveSubscription',
		'RemoveSubscriptionButton' => 'RemoveSubscription'
	),
	'post_action_parameters' => array(
		'Cancel' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel'
		),
		'SubmitNewsletterUser' => array(
			'NewsletterUser' => 'NewsletterUser',
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'SubscribeNewsletterUser' => array(
			'Email' => 'Email',
			'MailingListContentObjectID' => 'MailingListContentObjectID',
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'ConfirmNewsletterUser' => array(
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'RemoveNewsletterUser' => array(
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'RemoveForGoodNewsletterUser' => array(
			'RedirectUrlActionRemove' => 'RedirectUrlActionRemove'
		),
		'ApproveSubscription' => array(
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'RemoveSubscription' => array(
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		)
	)
);

$ViewList['blacklist'] = array(
	'script' => 'blacklist.php',
	'functions' => array( 'blacklist' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'ui_context' => 'navigation',
	'params' => array(),
	'single_post_actions' => array(
		'CancelButton' => 'Cancel',
		'AddBacklistItemButton' => 'AddBacklistItem',
		'RemoveBacklistItemButton' => 'RemoveBacklistItem'
	),
	'post_action_parameters' => array(
		'Cancel' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel'
		),
		'AddBacklistItem' => array(
			'Email' => 'Email',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'RemoveBacklistItem' => array(
			'Email' => 'Email',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		)
	)
);



$ViewList['send'] = array(
	'script' => 'send.php',
	'functions' => array( 'send' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array(),
	'single_post_actions' => array(
		'SendNewsletterButton' => 'SendNewsletter',
		'AbortNewsletterButton' => 'AbortNewsletter',
		'SendNewsletterTestButton' => 'SendNewsletterTest'
	),
	'post_action_parameters' => array(
		'SendNewsletter' => array(
			'ContentNodeID' => 'ContentNodeID',
			'ContentObjectID' => 'ContentObjectID'
		),
		'AbortNewsletter' => array(
			'ContentNodeID' => 'ContentNodeID',
			'ContentObjectID' => 'ContentObjectID'
		),
		'SendNewsletterTest' => array(
			'ContentNodeID' => 'ContentNodeID',
			'ContentObjectID' => 'ContentObjectID',
			'TestReceiverEmail' => 'TestReceiverEmail'
		)
	)
);

$ViewList['subscribe'] = array(
	'script' => 'subscribe.php',
	'functions' => array( 'subscribe' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'single_post_actions' => array(
		'SubscribeButton' => 'Subscribe',
		'CancelButton' => 'Cancel'
	),
	'post_action_parameters' => array(
		'Subscribe' => array(
			'NewsletterUser' => 'NewsletterUser',
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		)
	)
);

$ViewList['unsubscribe'] = array(
	'script' => 'unsubscribe.php',
	'functions' => array( 'unsubscribe' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array( 'Hash' ),
	'single_post_actions' => array(
		'UnsubscribeButton' => 'Unsubscribe',
		'CancelButton' => 'Cancel'
	)
);

$ViewList['preview'] = array(
	'script' => 'preview.php',
	'functions' => array( 'preview' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array( 'EditionContentobjectId' ) );

$FunctionList['subscribe'] = array();
$FunctionList['configure'] = array();
$FunctionList['unsubscribe'] = array();
$FunctionList['manage_subscription'] = array();
$FunctionList['manage_user'] = array();
$FunctionList['preview'] = array();
$FunctionList['archive'] = array();
$FunctionList['index'] = array();
$FunctionList['settings'] = array();
$FunctionList['send'] = array();
$FunctionList['manage_mailbox'] = array();
$FunctionList['blacklist'] = array();
$FunctionList['manage_import'] = array();

$FunctionList['admin'] = array(); // for display / hide of admin menue