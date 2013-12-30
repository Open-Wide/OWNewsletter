<?php

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();
$errors = array();
/* Retrieval parameters */
if ( $module->hasActionParameter( 'ContentNodeID' ) ) {
	$contentNodeID = $module->actionParameter( 'ContentNodeID' );
	$contentNode = eZFunctionHandler::execute( 'content', 'node', array( 'node_id' => $contentNodeID ) );
	if ( !$contentNode instanceof eZContentObjectTreeNode ) {
		$errors[] = "Invalide content node ID";
	} else {
		$contentDataMap = $contentNode->dataMap();
		foreach ( $contentDataMap as $attribute ) {
			if ( $attribute->attribute( 'data_type_string' ) == 'ownewsletteredition' ) {
				$newsletterEdition = $attribute->content();
				break;
			}
		}
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
	$errors[] = "Content node ID is missing";
}
if ( $module->hasActionParameter( 'ContentObjectID' ) ) {
	$contentObjectID = $module->actionParameter( 'ContentObjectID' );
	$contentObject = eZFunctionHandler::execute( 'content', 'object', array( 'object_id' => $contentObjectID ) );
	if ( !$contentObject instanceof eZContentObject ) {
		$errors[] = "Invalide content object ID";
	}
} else {
	$errors[] = "Content object ID is missing";
}

/* If press Send test newsletter button */
if ( $module->isCurrentAction( 'SendNewsletterTest' ) ) {
	if ( $module->hasActionParameter( 'EmailReceiverTest' ) ) {
		$emailReceiverTest = $module->actionParameter( 'EmailReceiverTest' );
	} else {
		$errors[] = "Email receiver is missing";
	}
}


if ( !empty( $errors ) ) {
	foreach ( $errors as $error ) {
		eZDebug::writeError( $error, 'Send newsletter' );
	}
} elseif ( $module->isCurrentAction( 'SendNewsletter' ) ) {
	OWNewsletterSending::create( $newsletter, $newsletterEdition );
	eZContentCacheManager::clearContentCacheIfNeeded( $contentObjectID );
} elseif ( $module->isCurrentAction( 'AbortNewsletter' ) ) {
	OWNewsletterSending::abort( $newsletterEdition );
	eZContentCacheManager::clearContentCacheIfNeeded( array( $contentObjectID ) );
} elseif ( $module->isCurrentAction( 'SendNewsletterTest' ) ) {
	OWNewsletterSending::sendTest( $newsletter, $newsletterEdition, $emailReceiverTest );
}
$module->redirectTo( $contentNode->attribute( 'url_alias' ) );
