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

}
