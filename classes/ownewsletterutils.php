<?php

class OWNewsletterUtils {

	/**
	 * generate a unique hash md5
	 *
	 * @param string $flexibleVar is used as a part of string for md5
	 * @return string md5
	 */
	static function generateUniqueMd5Hash( $flexibleVar = '' ) {
		$stringForHash = $flexibleVar . '-' . microtime( true ) . '-' . mt_rand() . '-' . mt_rand();
		return md5( $stringForHash );
	}

	/**
	 * Convert array to string
	 * ;$1;$2;$3;
	 * for searching : begin and end is ";"
	 * like %;$1;%
	 *
	 * @param array $array
	 * @return string
	 */
	static function arrayToString( $array ) {
		return ';' . implode( ';', $array ) . ';';
	}

	/**
	 * Convert string to array
	 * ;$1;$2;$3; to array( $1, $2, $3 )
	 *
	 * @param $string
	 * @return unknown_type
	 */
	static function stringToArray( $string ) {
		return explode( ';', substr( $string, 1, strlen( $string ) - 2 ) );
	}

}
