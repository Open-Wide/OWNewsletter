<?php

class OWNewsletterException extends Exception {

    public function __construct( $message = "", $code = 0, $previous = NULL ) {
        eZDebug::writeError( $message, "OWNewsletterException" );
        parent::__construct( $message, $code, $previous );
    }

}
