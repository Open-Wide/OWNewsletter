<?php

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();
$errors = array();

$Result['path'] = array();

/* Retrieval parameters */
if ( $module->hasActionParameter( 'ContentNodeID' ) ) {
	$contentNodeID = $module->actionParameter( 'ContentNodeID' );
	$contentNode = eZFunctionHandler::execute( 'content', 'node', array( 'node_id' => $contentNodeID ) );
	if ( !$contentNode instanceof eZContentObjectTreeNode ) {
		$errors[] = "Node not found.";
	} else {
		$tpl->setVariable( 'node', $contentNode );
		$contentDataMap = $contentNode->dataMap();
		foreach ( $contentDataMap as $attribute ) {
			if ( $attribute->attribute( 'data_type_string' ) == 'ownewsletteredition' ) {
				$newsletterEdition = $attribute->content();
				break;
			}
		}
		foreach ( $contentNode->attribute( 'path' ) as $path ) {
			$Result['path'][] = array(
				'url' => $path->attribute( 'url_alias' ),
				'text' => $path->attribute( 'name' )
			);
		}
		$Result['path'][] = array(
				'url' => $contentNode->attribute( 'url_alias' ),
				'text' => $contentNode->attribute( 'name' )
			);
		$contentParentNode = $contentNode->attribute( 'parent' );
		$contentParentNodeDataMap = $contentParentNode->dataMap();
		foreach ( $contentParentNodeDataMap as $attribute ) {
			if ( $attribute->attribute( 'data_type_string' ) == 'ownewsletter' ) {
				$newsletter = $attribute->content();
				break;
			}
		}
		if ( !isset( $newsletter ) ) {
			$errors[] = "Newsletter configuration not found.";
		}
		if ( !isset( $newsletterEdition ) ) {
			$errors[] = "Newsletter edition configuration not found.";
		}
	}
} else {
	$errors[] = "Content node ID is missing.";
}
if ( $module->hasActionParameter( 'ContentObjectID' ) ) {
	$contentObjectID = $module->actionParameter( 'ContentObjectID' );
	$contentObject = eZFunctionHandler::execute( 'content', 'object', array( 'object_id' => $contentObjectID ) );
	if ( !$contentObject instanceof eZContentObject ) {
		$errors[] = "Content object nor found.";
	}
} else {
	$errors[] = "Content object ID is missing.";
}

/* If press Send test newsletter button */
if ( $module->isCurrentAction( 'SendNewsletterTest' ) ) {
	if ( $module->hasActionParameter( 'TestReceiverEmail' ) ) {
		$testReceiverEmail = $module->actionParameter( 'TestReceiverEmail' );
	} else {
		$errors[] = "E-mail receivers are missing.";
	}
}

/* If press Send test newsletter button */
if ( $module->isCurrentAction( 'SendNewsletterFromDate' ) ) {
	if ( $module->hasActionParameter( 'NewsletterSendingDate' ) && $module->actionParameter( 'NewsletterSendingDate' ) != '' ) {
		$newsletterSendingDate = $module->actionParameter( 'NewsletterSendingDate' );
		try {
			$newsletterSendingDate = new DateTime( $newsletterSendingDate );
			$newsletterSendingDate = $newsletterSendingDate->format( 'U' );
		} catch ( Exception $ex ) {
			$errors[] = "The sending date has no good format. Wanted YYYY-MM-DD.";
		}
	} else {
		$errors[] = "Sending date is missing.";
	}
}

$tpl->setVariable( 'current_action', $module->currentAction() );
if ( !empty( $errors ) ) {
	foreach ( $errors as $index => $error ) {
		eZDebug::writeError( $error, 'Send newsletter' );
		$errors[$index] = ezpI18n::tr( 'newsletter/warning_message', $error );
	}
	$tpl->setVariable( 'errors', $errors );
} elseif ( $module->isCurrentAction( 'SendNewsletterSoonAsPossible' ) ) {
	OWNewsletterSending::send( $newsletterEdition );
} elseif ( $module->isCurrentAction( 'SendNewsletterFromDate' ) ) {
	OWNewsletterSending::send( $newsletterEdition, $newsletterSendingDate );
	$tpl->setVariable( 'newsletter_sending_date', $newsletterSendingDate );
} elseif ( $module->isCurrentAction( 'AbortNewsletter' ) ) {
	OWNewsletterSending::abort( $newsletterEdition );
} elseif ( $module->isCurrentAction( 'SendNewsletterTest' ) ) {
	$sendingResultList = OWNewsletterSending::sendTest( $newsletterEdition, $testReceiverEmail );
	foreach ( $sendingResultList as $sendingResult ) {
		if ( $sendingResult['send_result'] == false ) {
			$errors[] = ezpI18n::tr( 'newsletter/warning_message', 'The sending at this address failed: %address.', null, array(
						'%address' => $sendingResult['email_receiver'] ) );
			$tpl->setVariable( 'errors', $errors );
		}
	}
	$tpl->setVariable( 'test_receiver_email', $testReceiverEmail );
}

$Result['content'] = $tpl->fetch( "design:newsletter/send.tpl" );

$Result['path'][] = array(
	'url' => false,
	'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' )
);
$Result['path'][] = array(
	'url' => false,
	'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Sending' )
);
