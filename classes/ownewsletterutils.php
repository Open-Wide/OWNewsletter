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

}
