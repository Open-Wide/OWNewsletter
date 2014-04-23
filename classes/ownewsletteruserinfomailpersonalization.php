<?php

class OWNewsletterUserInfoMailPersonalization {

    static function applyOnSubject( $subject, $newsletterUser ) {
        return static::apply( $subject, $newsletterUser );
    }

    static function applyOnHTMLBody( $HTMLBody, $newsletterUser ) {
        return static::apply( $HTMLBody, $newsletterUser );
    }

    static function applyOnPlainTextBody( $plainTextBody, $newsletterUser ) {
        return static::apply( $plainTextBody, $newsletterUser );
    }

    protected static function apply( $text, $newsletterUser ) {
        $searchArray = array(
            '[[name]]',
            '[[salutation_name]]',
            '[[first_name]]',
            '[[last_name]]'
        );
        $replaceArray = array(
            $newsletterUser->attribute( 'name' ),
            $newsletterUser->attribute( 'salutation_name' ),
            $newsletterUser->attribute( 'first_name' ),
            $newsletterUser->attribute( 'last_name' )
        );
        return str_replace( $searchArray, $replaceArray, $text );
    }

}
