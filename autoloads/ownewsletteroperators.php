<?php

class OWNewsletterOperators {

	var $Operators;

	function __construct() {
		$this->Operators = array( 'newsletter_edition_content', 'newsletter_str_replace' );
	}

	/* ! Returns the template operators.
	 */

	function operatorList() {
		return $this->Operators;
	}

	function namedParameterPerOperator() {
		return true;
	}

	function namedParameterList() {
		return array(
			'newsletter_edition_content' => array(),
			'newsletter_str_replace' => array(
				'string_replace' => array( 
					'type' => 'string',
					'required' => true,
					'default' => '' ) ) );
	}

	function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters ) {
		switch ( $operatorName ) {
			case 'newsletter_edition_content':
				$operatorValue = self::getNewsletterEdionContent( $operatorValue );
				break;
			case 'newsletter_str_replace':
				$operatorValue = str_replace( array_keys( $namedParameters['string_replace'] ), array_values( $namedParameters['string_replace'] ), $operatorValue );
				break;
		}
	}

	protected function getNewsletterEdionContent( &$operatorValue ) {
		if ( $operatorValue instanceof eZContentObject || $operatorValue instanceof eZContentObjectTreeNode ) {
			$dataMap = $operatorValue->dataMap();
			$operatorValue = null;
			foreach ( $dataMap as $attribute ) {
				if ( $attribute->attribute( 'data_type_string' ) == 'ownewsletteredition' ) {
					$operatorValue = $attribute->content();
					break;
				}
			}
		}
		if ( $operatorValue == null ) {
			eZDebug::writeError( "Newsletter edtion attribute not found", "OWNewsletterOperators" );
		}
		return $operatorValue;
	}

}

?>