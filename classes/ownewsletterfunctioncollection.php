<?php

/*
 * Fetch functions for newsletter module
 */

class OWNewsletterFunctionCollection {

	static function fetchEditionClassList() {
		$ini = eZINI::instance( 'newsletter.ini' );
		if ( !$ini->hasVariable( 'NewsletterSettings', 'NewsletterEditionContentClassGroup' ) ) {
			eZDebug::writeError( "[NewsletterSettings]NewsletterEditionContentClassGroup is missing in newsletter.ini" );
			return $result = array(
				'result' => false );
		}
		$classGroupName = $ini->variable( 'NewsletterSettings', 'NewsletterEditionContentClassGroup' );
		$classGroup = eZContentClassGroup::fetchByName( $classGroupName );
		if ( !$classGroup instanceof eZContentClassGroup ) {
			eZDebug::writeError( "Class group $classGroupName not found." );
			return $result = array(
				'result' => false );
		}
		$classGroupID = $classGroup->attribute( 'id' );
		return $result = array(
			'result' => eZContentClassClassGroup::fetchClassList( 0, $classGroupID ) );
	}

}
