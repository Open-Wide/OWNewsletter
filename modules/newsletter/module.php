<?php

$Module = array(
	"name" => "OW Newsletter" );

$ViewList = array();

$ViewList['index'] = array(
	'script' => 'index.php',
	'functions' => array(
		'index' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'params' => array() );

$ViewList['user'] = array(
	'script' => 'user.php',
	'functions' => array(
		'user_list',
		'user_view',
		'user_create',
		'user_edit',
		'user_remove' ),
	'default_navigation_part' => 'eznewsletternavigationpart',
	'ui_context' => 'admin',
	'params' => array( 'newsletterUserID', 'mailingListContentobjectID' ),
	'single_post_actions' => array(
		'CancelButton' => 'Cancel',
		'SubmitNewsletterUserButton' => 'SubmitNewsletterUser',
		'RemoveNewsletterUserButton' => 'RemoveNewsletterUser',
		'ApproveSubscriptionButton' => 'ApproveSubscription',
		'RemoveSubscriptionButton' => 'RemoveSubscription'
	),
	 'post_action_parameters' => array(
		 'Cancel'=> array(
			 'RedirectUrlActionCancel' => 'RedirectUrlActionCancel'
		 ),
		 'SubmitNewsletterUser'=> array(
			 'NewsletterUser' => 'NewsletterUser',
			 'RedirectUrlActionCancel' => 'RedirectUrlActionCancel',
			 'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		 ),
		 'RemoveNewsletterUser'=> array(
			 'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		 ),
		 'ApproveSubscription'=> array(
			 'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		 ),
		 'RemoveSubscription'=> array(
			 'RedirectUrlActionSuccess' => 'RedirectUrlActionSuccess'
		 )
	)
);

$FunctionList['subscribe'] = array();
$FunctionList['configure'] = array();
$FunctionList['unsubscribe'] = array();
$FunctionList['subscription_list_csvimport'] = array();
$FunctionList['subscription_list_csvimport_import'] = array();
$FunctionList['subscription_list_csvexport'] = array();
$FunctionList['subscription_list'] = array();
$FunctionList['subscription_view'] = array();
$FunctionList['user_list'] = array();
$FunctionList['user_view'] = array();
$FunctionList['user_remove'] = array();
$FunctionList['user_edit'] = array();
$FunctionList['user_create'] = array();
$FunctionList['preview'] = array();
$FunctionList['archive'] = array();
$FunctionList['index'] = array();
$FunctionList['settings'] = array();
$FunctionList['send'] = array();
$FunctionList['mailbox_item_list'] = array();
$FunctionList['mailbox_item_view'] = array();
$FunctionList['mailbox_list'] = array();
$FunctionList['mailbox_edit'] = array();
$FunctionList['blacklist_item'] = array();
$FunctionList['blacklist_item'] = array();
$FunctionList['import_list'] = array();
$FunctionList['import_view'] = array();

$FunctionList['admin'] = array(); // for display / hide of admin menue