<?php

/*
 * Fetch functions for newsletter module
 */

class OWNewsletterFunctionCollection {

	/**
	 * Fetch all content classes in the newsletter edition class group
	 * 
	 * @return array of eZContentClass
	 */
	static function fetchEditionClassList() {
		return array(
			'result' => self::getEditionClassList() );
	}

	/**
	 * Fetch all content classes in the newsletter edition class group
	 * 
	 * @return array of content class identifier
	 */
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

	/**
	 * Prepare the content class list
	 * 
	 * @return array of eZContentClass
	 */
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

	/**
	 * Fetch users with custom parameter
	 * 
	 * @param integer $mailing_list_contentobject_id
	 * @param string $user_status
	 * @param string $subscription_status
	 * @param integer $limit
	 * @param integer $offset
	 * @return array of OWNewsletterSubscription
	 */
	static function fetchUsers( $mailing_list_contentobject_id, $user_status, $subscription_status, $limit, $offset ) {
		$conds = array();
		if ( $mailing_list_contentobject_id !== FALSE ) {
			$conds['subscription']['mailing_list_contentobject_id'] = (int) $mailing_list_contentobject_id;
		}
		if ( is_numeric( $user_status ) ) {
			$user_status = self::getUserStatus( $user_status );
			$conds['status'] = is_array( $user_status ) ? array( $user_status ) : (int) $user_status;
		}
		if ( is_numeric( $subscription_status ) ) {
			$subscription_status = self::getSubscriptionStatus( $subscription_status );
			$conds['subscription']['status'] = is_array( $subscription_status ) ? array( $subscription_status ) : (int) $subscription_status;
		}
		return array( 'result' => OWNewsletterUser::fetchListWithSubsricption( $conds, $limit, $offset ) );
	}

	/**
	 * Count subscriptions with custom parameter
	 * 
	 * @param integer $mailing_list_contentobject_id
	 * @param string $user_status
	 * @param string $subscription_status
	 * @return integer
	 */
	static function countUsers( $mailing_list_contentobject_id, $user_status, $subscription_status ) {
		$conds = array();
		if ( $mailing_list_contentobject_id !== FALSE ) {
			$conds['subscription']['mailing_list_contentobject_id'] = (int) $mailing_list_contentobject_id;
		}
		if ( is_numeric( $user_status ) ) {
			$user_status = self::getUserStatus( $user_status );
			$conds['status'] = is_array( $user_status ) ? array( $user_status ) : (int) $user_status;
		}
		if ( is_numeric( $subscription_status ) ) {
			$subscription_status = self::getSubscriptionStatus( $subscription_status );
			$conds['subscription']['status'] = is_array( $subscription_status ) ? array( $subscription_status ) : (int) $subscription_status;
		}
		return array( 'result' => OWNewsletterUser::countListWithSubsricption( $conds ) );
	}

	/**
	 * Fetch subscriptions with custom parameter
	 * 
	 * @param integer $mailing_list_contentobject_id
	 * @param string $subscription_status
	 * @param integer $limit
	 * @param integer $offset
	 * @return array of OWNewsletterSubscription
	 */
	static function fetchSubscriptions( $mailing_list_contentobject_id, $subscription_status, $limit, $offset ) {
		$conds = array();
		if ( $mailing_list_contentobject_id !== FALSE ) {
			$conds['mailing_list_contentobject_id'] = (int) $mailing_list_contentobject_id;
		}
		if ( is_numeric( $subscription_status ) ) {
			$subscription_status = self::getSubscriptionStatus( $subscription_status );
			$conds['status'] = is_array( $subscription_status ) ? array( $subscription_status ) : (int) $subscription_status;
		}
		return array( 'result' => OWNewsletterSubscription::fetchList( $conds, $limit, $offset ) );
	}

	/**
	 * Count subscriptions with custom parameter
	 * 
	 * @param integer $mailing_list_contentobject_id
	 * @param string $status
	 * @return integer
	 */
	static function countSubscriptions( $mailing_list_contentobject_id, $subscription_status ) {
		$conds = array();
		if ( $mailing_list_contentobject_id !== FALSE ) {
			$conds['mailing_list_contentobject_id'] = (int) $mailing_list_contentobject_id;
		}
		if ( is_numeric( $subscription_status ) ) {
			$subscription_status = self::getSubscriptionStatus( $subscription_status );
			$conds['status'] = is_array( $subscription_status ) ? array( $subscription_status ) : (int) $subscription_status;
		}
		return array( 'result' => OWNewsletterSubscription::countList( $conds ) );
	}

	/**
	 * Transform subscription status string in system status value
	 * @param string $status
	 * @return integer
	 */
	static protected function getUserStatus( $status ) {
		switch ( $status ) {
			case 'pending':
				return OWNewsletterUser::STATUS_PENDING;
			case 'confirmed':
				return OWNewsletterUser::STATUS_CONFIRMED;
			case 'bounced':
				return array(
					OWNewsletterUser::STATUS_BOUNCED_SOFT,
					OWNewsletterUser::STATUS_BOUNCED_HARD );
			case 'bounced_soft':
				return OWNewsletterUser::STATUS_BOUNCED_SOFT;
			case 'bounced_hard':
				return OWNewsletterUser::STATUS_BOUNCED_HARD;
			case 'removed':
				return array(
					OWNewsletterUser::STATUS_REMOVED_SELF,
					OWNewsletterUser::STATUS_REMOVED_ADMIN );
			case 'removed_self':
				return OWNewsletterUser::STATUS_REMOVED_SELF;
			case 'removed_admin':
				return OWNewsletterUser::STATUS_REMOVED_ADMIN;
			case 'blacklisted':
				return OWNewsletterUser::STATUS_BLACKLISTED;
			default:
				return false;
		}
	}

	/**
	 * Transform subscription status string in system status value
	 * @param string $status
	 * @return integer
	 */
	static protected function getSubscriptionStatus( $status ) {
		switch ( $status ) {
			case 'pending':
				return OWNewsletterSubscription::STATUS_PENDING;
			case 'confirmed':
				return OWNewsletterSubscription::STATUS_CONFIRMED;
			case 'approved':
				return OWNewsletterSubscription::STATUS_APPROVED;
			case 'bounced':
				return array(
					OWNewsletterSubscription::STATUS_BOUNCED_SOFT,
					OWNewsletterSubscription::STATUS_BOUNCED_HARD );
			case 'bounced_soft':
				return OWNewsletterSubscription::STATUS_BOUNCED_SOFT;
			case 'bounced_hard':
				return OWNewsletterSubscription::STATUS_BOUNCED_HARD;
			case 'removed':
				return array(
					OWNewsletterSubscription::STATUS_REMOVED_SELF,
					OWNewsletterSubscription::STATUS_REMOVED_ADMIN );
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

	/**
	 * Fetch the list of all available subscription status
	 */
	static function fetchAvailableSubscriptionStatus() {
		return array( 'result' => OWNewsletterSubscription::getAvailableStatus() );
	}

}
