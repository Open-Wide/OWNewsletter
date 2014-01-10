<?php

$Module = array(
	"name" => "OW Newsletter" );

$ViewList = array();

$ViewList['index'] = array(
	'script' => 'index.php',
	'functions' => array( 'index' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array()
);

$ViewList['user'] = array(
	'script' => 'user.php',
	'functions' => array( 'user' ),
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
		'RemoveSubscriptionButton' => 'RemoveSubscription',
		'UserSearchButton' => 'UserSearch'
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
		),
		'UserSearch' => array(
			'SearchUserEmail' => 'SearchUserEmail'
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
		'SendNewsletterSoonAsPossibleButton' => 'SendNewsletterSoonAsPossible',
		'SendNewsletterFromDateButton' => 'SendNewsletterFromDate',
		'AbortNewsletterButton' => 'AbortNewsletter',
		'SendNewsletterTestButton' => 'SendNewsletterTest'
	),
	'post_action_parameters' => array(
		'SendNewsletterSoonAsPossible' => array(
			'ContentNodeID' => 'ContentNodeID',
			'ContentObjectID' => 'ContentObjectID'
		),
		'SendNewsletterFromDate' => array(
			'ContentNodeID' => 'ContentNodeID',
			'ContentObjectID' => 'ContentObjectID',
			'NewsletterSendingDate' => 'NewsletterSendingDate'
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

$ViewList['configure'] = array(
	'script' => 'configure.php',
	'functions' => array( 'configure' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array( 'Hash' ),
	'single_post_actions' => array(
		'ConfigureButton' => 'Configure',
		'CancelButton' => 'Cancel'
	),
	'post_action_parameters' => array(
		'Configure' => array(
			'NewsletterUser' => 'NewsletterUser',
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		)
	)
);

$ViewList['preview'] = array(
	'script' => 'preview.php',
	'functions' => array( 'preview' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array(
		'EditionContentobjectId'
	)
);

$ViewList['mailbox'] = array(
	'script' => 'mailbox.php',
	'functions' => array( 'mailbox' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array( 'mailboxID' ),
	'single_post_actions' => array(
		'CancelButton' => 'Cancel',
		'SubmitMailboxButton' => 'SubmitMailbox',
		'DisableMailboxButton' => 'DisableMailbox',
		'EnableMailboxButton' => 'EnableMailbox',
		'RemoveMailboxButton' => 'RemoveMailbox'
	),
	'post_action_parameters' => array(
		'Cancel' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel'
		),
		'SubmitMailbox' => array(
			'Mailbox' => 'Mailbox',
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'DisableMailbox' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'EnableMailbox' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
		'RemoveMailbox' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		),
	)
);

$ViewList['bounce'] = array(
	'script' => 'bounce.php',
	'functions' => array( 'mailbox' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array(),
	'single_post_actions' => array(
		'ConnectMailboxButton' => 'ConnectMailbox',
		'ParseEmailsButton' => 'ParseEmails'
	)
);

$ViewList['settings'] = array(
	'script' => 'settings.php',
	'functions' => array( 'settings' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array()
);

$ViewList['subscription_import'] = array(
	'script' => 'subscription_import.php',
	'functions' => array( 'subscription_import' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array( 'mailingListID' ),
	'single_post_actions' => array(
		'CancelButton' => 'Cancel',
		'ImportButton' => 'Import'
	),
	'post_action_parameters' => array(
		'Cancel' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel'
		),
		'Import' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess',
			'ColumnDelimiter' => 'ColumnDelimiter',
			'UploadFile' => 'UploadFile'
		),
	)
);

$ViewList['subscription_export'] = array(
	'script' => 'subscription_export.php',
	'functions' => array( 'subscription_export' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array( 'mailingListID' ),
	'single_post_actions' => array(
		'CancelButton' => 'Cancel',
		'ExportButton' => 'Export'
	),
	'post_action_parameters' => array(
		'Cancel' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel'
		),
		'Export' => array(
			'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess',
			'ColumnDelimiter' => 'ColumnDelimiter',
			'SubscriptionStatus' => 'SubscriptionStatus',
			'SubscriptionFields' => 'SubscriptionFields'
		),
	)
);

$FunctionList['subscribe'] = array();
$FunctionList['configure'] = array();
$FunctionList['unsubscribe'] = array();
$FunctionList['subscription'] = array();
$FunctionList['user'] = array();
$FunctionList['preview'] = array();
$FunctionList['archive'] = array();
$FunctionList['index'] = array();
$FunctionList['settings'] = array();
$FunctionList['send'] = array();
$FunctionList['mailbox'] = array();
$FunctionList['blacklist'] = array();
$FunctionList['subscription_import'] = array();
$FunctionList['subscription_export'] = array();

$FunctionList['admin'] = array(); // for display / hide of admin menue