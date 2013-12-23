<?php

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();

/* Get views parameters */
$viewParameters = array( 'status' => FALSE, 'offset' => 0 );
if ( is_array( $Params['UserParameters'] ) ) {
	$viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}
$tpl->setVariable( 'view_parameters', $viewParameters );

/* Retrieval of cancal and success redirect URLs */
$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/blacklist';
if ( $module->hasActionParameter( 'RedirectUrlActionCancel' ) ) {
	$redirectUrlCancel = $module->actionParameter( 'RedirectUrlActionCancel' );
}
if ( $module->hasActionParameter( 'RedirectUrlActionSuccess' ) ) {
	$redirectUrlSuccess = $module->actionParameter( 'RedirectUrlActionSuccess' );
}

/* If press Cancel button */
if ( $module->isCurrentAction( 'Cancel' ) ) {
	$module->redirectTo( $redirectUrlCancel );
}

if ( $module->isCurrentAction( 'AddBacklistItem' ) ) {
	if ( $module->hasActionParameter( 'Email' ) ) {
		OWNewsletterBlacklistItem::addToBlacklist( $module->actionParameter( 'Email' ) );
		$module->redirectTo( $redirectUrlSuccess );
	} else {
		$tpl->setVariable( 'error', 'Email is required' );
	}
} elseif ( $module->isCurrentAction( 'RemoveBacklistItem' ) ) {
	if ( $module->hasActionParameter( 'Email' ) ) {
		OWNewsletterBlacklistItem::removeFromBlacklist( $module->actionParameter( 'Email' ) );
		$module->redirectTo( $redirectUrlSuccess );
	} else {
		$tpl->setVariable( 'error', 'Email is required' );
	}
} else {
	$limit = 10;
	$limitArray = array( 10, 10, 25, 50 );
	$limitArrayKey = eZPreferences::value( 'admin_blacklist_item_list_limit' );
	if ( isset( $limitArray[$limitArrayKey] ) ) {
		$limit = $limitArray[$limitArrayKey];
	}

	$tpl->setVariable( 'blacklist_item_list', OWNewsletterBlacklistItem::fetchList(array(), $limit, $viewParameters[ 'offset' ]) );
	$tpl->setVariable( 'blacklist_item_list_count', OWNewsletterBlacklistItem::countList() );
	/* Initilize module result */
	$Result = array();
	$Result['path'] = array(
		array(
			'url' => 'newsletter/index',
			'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
		array(
			'url' => 'newsletter/blacklist',
			'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Blacklist' ) ) );
	$Result['content'] = $tpl->fetch( 'design:newsletter/blacklist/list.tpl' );
}