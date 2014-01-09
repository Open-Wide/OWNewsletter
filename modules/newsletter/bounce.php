<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();

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
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Bounces' ) ) );

/* Retrieval of cancal and success redirect URLs */
$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/bounce';
if ( $module->hasActionParameter( 'RedirectUrlActionCancel' ) ) {
	$redirectUrlCancel = $module->actionParameter( 'RedirectUrlActionCancel' );
}
if ( $module->hasActionParameter( 'RedirectUrlActionSuccess' ) ) {
	$redirectUrlSuccess = $module->actionParameter( 'RedirectUrlActionSuccess' );
}
$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_success', $redirectUrlSuccess );


if ( $module->isCurrentAction( 'ConnectMailbox' ) ) {
	$collectMailResult = OWNewsletterMailbox::collectMailsFromActiveMailboxes();
	$tpl->setVariable( 'collect_mail_result', $collectMailResult );
} elseif ( $module->isCurrentAction( 'ParseEmails' ) ) {
	$parseResultArray = OWNewsletterBounce::parseActiveItems();
	$tpl->setVariable( 'parse_result', $parseResultArray );
}

$limit = 10;
$limitArray = array( 10, 10, 25, 50 );
$limitArrayKey = eZPreferences::value( 'admin_bounce_list_limit' );
if ( isset( $limitArray[$limitArrayKey] ) ) {
	$limit = $limitArray[$limitArrayKey];
}
$conds = array();
$tpl->setVariable( 'bounce_list', OWNewsletterBounce::fetchList( $conds, $limit, $viewParameters['offset'] ) );
$tpl->setVariable( 'all_bounce_list_count', OWNewsletterBounce::countList() );
$tpl->setVariable( 'bounce_list_count', OWNewsletterBounce::countList( $conds ) );
$Result['content'] = $tpl->fetch( "design:newsletter/bounce/list.tpl" );

