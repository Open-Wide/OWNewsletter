<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();

/* Initilize module result */
$Result = array();
$Result['path'] = array(
	array(
		'url' => false,
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
	array(
		'url' => false,
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Import subscriptions' ) ) );


$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/subscription_import';
$subscriptionStatus = array( OWNewsletterSubscription::STATUS_APPROVED );
$subscriptionFields = array( "email" );
$columnDelimiter = ";";
$mailingListID = $Params['mailingListID'];
if ( empty( $mailingListID ) ) {
	$tpl->setVariable( 'warning', ezpI18n::tr( 'newsletter/warning_message', 'Mailing list is missing.' ) );
} else {
	$contentObject = eZContentObject::fetch( $mailingListID );
	if ( $contentObject instanceof eZContentObject ) {
		$tpl->setVariable( 'mailing_list', $contentObject );
		$node = $contentObject->attribute( 'main_node' );
		$Result['path'][] = array(
			'url' => false,
			'text' => $node->attribute( 'name' ) );
		$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/subscription_import/' . $mailingListID;
		if ( $module->isCurrentAction( 'Import' ) ) {
			if ( $module->hasActionParameter( 'ColumnDelimiter' ) ) {
				$columnDelimiter = $module->actionParameter( 'ColumnDelimiter' );
			}
			if ( eZHTTPFile::canFetch( 'UploadFile' ) ) {
				$binaryFile = eZHTTPFile::fetch( 'UploadFile' );
				ini_set( 'auto_detect_line_endings', TRUE );
				$handle = fopen( $binaryFile->Filename, 'r' );
				OWScriptLogger::startLog( 'subscription_import' );
				$rowCount = 0;
				$createdCount = 0;
				$subscriptionCount = 0;
				while ( ($row = fgetcsv( $handle, 0, $columnDelimiter ) ) !== FALSE ) {
					if ( !isset( $fileHeaders ) ) {
						$fileHeaders = $row;
					} else {
						$rowCount++;
						$userInfo = array( 'status' => OWNewsletterUser::STATUS_CONFIRMED );
						foreach ( $row as $index => $field ) {
							$fieldIdentifier = $fileHeaders[$index];
							switch ( $fieldIdentifier ) {
								case 'email':
								case 'first_name':
								case 'last_name':
								case 'salutation':
									$userInfo[$fieldIdentifier] = $field;
									break;
							}
						}
						if ( isset( $userInfo['email'] ) && !empty( $userInfo['email'] ) && ezcMailTools::validateEmailAddress( $userInfo['email'] ) ) {
							$user = OWNewsletterUser::fetchByEmail( $userInfo['email'] );
							if ( !$user instanceof OWNewsletterUser ) {
								$user = OWNewsletterUser::createOrUpdate( $userInfo, 'import' );
								OWScriptLogger::logNotice( "Row #$rowCount : user created (" . $userInfo['email'] . ")", 'process_row' );
								$createdCount++;
							}
							$user->subscribeTo( $mailingListID, OWNewsletterSubscription::STATUS_APPROVED, 'import' );
							OWScriptLogger::logNotice( "Row #$rowCount : user subscribe to the mailing list", 'process_row' );
							$subscriptionCount++;
						} else {
							OWScriptLogger::logError( "Row #$rowCount : failed to import, e-mail is missing or invalid", 'process_row' );
						}
					}
				}
				OWScriptLogger::logNotice( "$rowCount rows processed" . PHP_EOL . "$subscriptionCount subscriptions created or updated" . PHP_EOL . "$createdCount users created", 'treatment_completed' );
				$logger = OWScriptLogger::instance();
				$tpl->setVariable( 'log_url', 'owscriptlogger/logs/' . $logger->attribute( 'id' ) );
				ini_set( 'auto_detect_line_endings', FALSE );
			} else {
				$tpl->setVariable( 'warning', ezpI18n::tr( 'newsletter/warning_message', 'File is missing.' ) );
			}
		}
	} else {
		$tpl->setVariable( 'warning', ezpI18n::tr( 'newsletter/warning_message', 'Mailing list not found.' ) );
	}
}
/* Retrieval of cancal and success redirect URLs */
if ( $module->hasActionParameter( 'RedirectUrlActionCancel' ) ) {
	$redirectUrlCancel = $module->actionParameter( 'RedirectUrlActionCancel' );
}
if ( $module->hasActionParameter( 'RedirectUrlActionSuccess' ) ) {
	$redirectUrlSuccess = $module->actionParameter( 'RedirectUrlActionSuccess' );
}
$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_success', $redirectUrlSuccess );

$userDefinition = OWNewsletterUser::definition();
$fieldList = array_merge( array_keys( $userDefinition['fields'] ), array_keys( $userDefinition['function_attributes'] ) );
sort( $fieldList );
$tpl->setVariable( 'available_field_list', $fieldList );
$tpl->setVariable( 'column_delimiter', $columnDelimiter );
$tpl->setVariable( 'selected_status_list', $subscriptionStatus );
$tpl->setVariable( 'selected_field_list', $subscriptionFields );
$Result['content'] = $tpl->fetch( "design:newsletter/subscriptions/import.tpl" );

