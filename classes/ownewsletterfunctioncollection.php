<?php

/*
 * Fetch functions for newsletter module
 */

class OWNewsletterFunctionCollection {

	static function fetchEditionClassList() {
		return array(
			'result' => self::getEditionClassList() );
	}

	static function fetchEditionClassIdentifierList() {
		$classList = self::getEditionClassList();
		if ( !is_array( $classList ) ) {
			return array(
				'result' => false );
		}
		$result = array();
		foreach ( $classList as $class ) {
			$result[] = $class->attribute( 'identifier' );
		}
		return array(
			'result' => $result );
	}

	static protected function getEditionClassList() {
		$ini = eZINI::instance( 'newsletter.ini' );
		if ( !$ini->hasVariable( 'NewsletterSettings', 'NewsletterEditionContentClassGroup' ) ) {
			eZDebug::writeError( "[NewsletterSettings]NewsletterEditionContentClassGroup is missing in newsletter.ini" );
			return false;
		}
		$classGroupName = $ini->variable( 'NewsletterSettings', 'NewsletterEditionContentClassGroup' );
		$classGroup = eZContentClassGroup::fetchByName( $classGroupName );
		if ( !$classGroup instanceof eZContentClassGroup ) {
			eZDebug::writeError( "Class group $classGroupName not found." );
			return false;
		}
		$classGroupID = $classGroup->attribute( 'id' );
		return eZContentClassClassGroup::fetchClassList( 0, $classGroupID );
	}

	static function fetchSubscriptions( $mailing_list_contentobject_id, $status ) {
		if ( $status ) {
			return array(
				'result' => OWNewsletterSubscription::fetchSubscriptionListByMailingListIdAndStatus( $mailing_list_contentobject_id, $status ) );
		}
		return array(
			'result' => OWNewsletterSubscription::fetchSubscriptionListByMailingListId( $mailing_list_contentobject_id ) );
	}

	static function countSubscriptions( $mailing_list_contentobject_id, $status ) {
		if ( $status ) {
			return array(
				'result' => count( OWNewsletterSubscription::fetchSubscriptionListByMailingListIdAndStatus( $mailing_list_contentobject_id, self::getSubscriptionStatus($status) ) ) );
		}
		return array(
			'result' => count( OWNewsletterSubscription::fetchSubscriptionListByMailingListId( $mailing_list_contentobject_id ) ) );
	}
	
	static protected function getSubscriptionStatus( $status ) {
		switch ( $status ) {
			case 'pending':
				return OWNewsletterSubscription::STATUS_PENDING;
			case 'confirmed':
				return OWNewsletterSubscription::STATUS_CONFIRMED;
			case 'approved':
				return OWNewsletterSubscription::STATUS_APPROVED;
			case 'bounced':
				return array(OWNewsletterSubscription::STATUS_BOUNCED_SOFT, OWNewsletterSubscription::STATUS_BOUNCED_HARD);
			case 'bounced_soft':
				return OWNewsletterSubscription::STATUS_BOUNCED_SOFT;
			case 'bounced_hard':
				return OWNewsletterSubscription::STATUS_BOUNCED_HARD;
			case 'removed':
				return array(OWNewsletterSubscription::STATUS_REMOVED_SELF, OWNewsletterSubscription::STATUS_REMOVED_ADMIN);
			case 'removed_self':
				return OWNewsletterSubscription::STATUS_REMOVED_SELF;
			case 'removed_admin':
				return OWNewsletterSubscription::STATUS_REMOVED_ADMIN;
			case 'blacklisted':
				return OWNewsletterSubscription::STATUS_BLACKLISTED;
			default:
				return false;
		}
	}

}
