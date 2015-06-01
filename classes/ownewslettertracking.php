<?php

class OWNewsletterTracking {

    protected $newsletterUserObject = false;
    protected $editionContentObject = false;
    protected $ini = false;

    /**
     * Constructor
     */
    public function __construct() {

        $this->ini = eZINI::instance( 'newsletter.ini' );
    }

    // ######################################
    // Functions used in process cronjob.
    // ######################################

    /**
     * Return new $owNewsletterTrackingClass object. Return false if NewsletterTracking is disabled or if TrackingClass doesn't exists
     * Used in process cronjob.
     *
     * @return OWNewsletterTracking or $owNewsletterTrackingClass
     */
    public static function create() {
        if( self::isEnabled() ) {
            $ini = eZINI::instance( 'newsletter.ini' );
            if( $ini->hasVariable( 'NewsletterTracking', 'TrackingClass' ) ) {
                $owNewsletterTrackingClass = $ini->variable( 'NewsletterTracking', 'TrackingClass' );
                if( class_exists( $owNewsletterTrackingClass ) ) {
                    return new $owNewsletterTrackingClass( );
                }
            }
        }
        return false;
    }

    /**
     * Return false if NewsletterTracking is disabled
     *
     * @return boolean
     */
    public static function isEnabled() {

        $ini = eZINI::instance( 'newsletter.ini' );
        return ( $ini->hasVariable( 'NewsletterTracking', 'NewsletterTracking' ) && $ini->variable( 'NewsletterTracking', 'NewsletterTracking' ) == 'enabled' );
    }

    /**
     * Assign $newsletterUserObject to current instance of OWNewsletterTracking
     * Used in process cronjob.
     *
     * @param OWNewsletterUser $newsletterUserObject
     * @return boolean
     */
    public function setNewsletterUserObject( $newsletterUserObject ) {
        if( $newsletterUserObject instanceof OWNewsletterUser ) {
            $this->newsletterUserObject = $newsletterUserObject;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Assign object with ID $editionContentObjectID to current instance of OWNewsletterTracking
     * Used in process cronjob.
     *
     * @param integer $editionContentObjectID
     * @return boolean
     */
    public function setEditionContentObjectId( $editionContentObjectID ) {
        if( $editionContentObjectID ) {
            $editionContentObject = eZFunctionHandler::execute( 'content', 'object', array( 'object_id' => $editionContentObjectID ) );
            if( $editionContentObject instanceof eZContentObject ) {
                $this->editionContentObject = $editionContentObject;
                return true;
            }
        }
        return false;
    }

    /**
     * Insert read and click merkers in $html (only if NewsletterTracking is enabled)
     * Used in process cronjob.
     *
     * @param string $html
     * @return string
     */
    public function insertMarkers( $html ) {
        if( $this->isEnabled() ) {
            $html = $this->insertClicMarkers( $html, $this->getClicMarker(), $this->getClicMarkerType() );
            $html = $this->insertReadMarker( $html, $this->getReadMarker() );
        }

        return $html;
    }

    // ######################################
    // Protected functions
    // ######################################

    /**
     * Return general placeholders which will be replaced in tracking markers
     *
     * @return array
     */
    protected function getPlaceholders() {

        $ini = eZINI::instance( 'site.ini' );
        return array( '{{LIST_ALIAS}}' => rawurlencode( $this->getListAlias() ),
            '{{NL_ALIAS}}' => rawurlencode( $this->getNlAlias() ),
            '{{USER_ID}}' => rawurlencode( $this->getUserID() ),
            '{{SITE_URL}}' => $this->getIniValue( 'SiteSettings', 'SiteURL' )
        );
    }

    /**
     * Replace all "{{.*}}" blocks in $marker.
     * 
     * @see ownewsletter.ini
     *
     * @param string $marker
     * @return string
     */
    protected function replacePlaceholders( $marker ) {

        // Settings blocks
        preg_match_all( '#{{SETTINGS=([^}]+)}}#', $marker, $settingsArray );
        foreach( $settingsArray[1] as $settingIdentifier ) {
            if( $settingValue = $this->getIniValue( 'CustomTrackingSettings', $settingIdentifier ) ) {
                $marker = str_replace( '{{SETTINGS=' . $settingIdentifier . '}}', $settingValue, $marker );
            }
        }

        // Simple blocks
        $placeHolders = $this->getPlaceholders();
        $searchArray = array();
        $replaceArray = array();
        foreach( $placeHolders as $search => $replace ) {
            $searchArray[] = $search;
            $replaceArray[] = $replace;
        }
        $marker = str_replace( $searchArray, $replaceArray, $marker );

        // Date blocks
        preg_match_all( '#{{DATE=([^}]+)}}#', $marker, $dateArray );
        $contentObjectTimestamp = time();
        foreach( $dateArray[1] as $dateFormat ) {
            $marker = str_replace( '{{DATE=' . $dateFormat . '}}', rawurlencode( date( $dateFormat, $contentObjectTimestamp ) ), $marker );
        }

        // Attribute blocks
        preg_match_all( '#{{ATTRIBUTE=([^}]+)}}#', $marker, $attrArray );
        foreach( $attrArray[1] as $attrIdentifier ) {
            $marker = str_replace( '{{ATTRIBUTE=' . $attrIdentifier . '}}', rawurlencode( $this->getNlAttribute( $attrIdentifier ) ), $marker );
        }

        // Delete others
        $marker = preg_replace( '#{{[^}]*}}#', '', $marker );

        return $marker;
    }

    /**
     * Insert $clickMarker in all links in $html, with type $clicMarkerType ('param' or 'anchor')
     *
     * @param string $html
     * @param string $clicMarker
     * @param string $clicMarkerType
     * @return string
     */
    protected function insertClicMarkers( $html, $clicMarker, $clicMarkerType = "param" ) {

        switch( $clicMarkerType ) {
            case 'anchor':
                $linkSearchPattern = '#(href)=("|\')(?!mailto)([^\'"]*)("|\')#';
                $linkReplacePattern = '$1="$3#' . $clicMarker . '"';
                break;

            case 'param':
                /* 1. Url with anchor and params
                 * 2. Url with only anchor
                 * 3. Url with only params
                 * 4. Url without anchor or params
                 */
                $linkSearchPattern = array( '#(href)=("|\')(?!mailto)([^\'"]*\?[^\'"]*)(\#[^\?"\'\#]+)("|\')#',
                    '#(href)=("|\')(?!mailto)([^\?"\']*)(\#[^\?"\'\#]+)("|\')#',
                    '#(href)=("|\')(?!mailto)([^\#\'"]*\?[^\#\'"]*)("|\')#',
                    '#(href)=("|\')(?!mailto)([^\#\?"\']*)("|\')#' );

                $linkReplacePattern = array( '$1="$3&' . $clicMarker . '$4"',
                    '$1="$3?' . $clicMarker . '$4"',
                    '$1="$3&' . $clicMarker . '"',
                    '$1="$3?' . $clicMarker . '"' );
                break;

            default:
                return $html;
        }

        $output = preg_replace( $linkSearchPattern, $linkReplacePattern, $html );

        return $output;
    }

    /**
     * Insert $readMarker before </body> tag
     *
     * @param string $html
     * @param string $readMarker
     * @return string
     */
    protected function insertReadMarker( $html, $readMarker ) {
        $output = str_replace( "</body>", $readMarker . "\n</body>", $html );
        return $output;
    }

    /**
     * Get clic marker type from newsletter.ini
     *
     * @return string
     */
    protected function getClicMarkerType() {
        if( $clicMarkerType = $this->getIniValue( 'NewsletterTracking', 'ClicMarkerType' ) ) {
            return $clicMarkerType;
        } else {
            return "param";
        }
    }

    /**
     * Get clic marker from newsletter.ini, and replace placeholders in this marker.
     *
     * @return string
     */
    protected function getClicMarker() {
        if( $marker = $this->getIniValue( 'NewsletterTracking', 'ClicMarker' ) ) {
            return $this->replacePlaceholders( $marker );
        } else {
            return '';
        }
    }

    /**
     * Get read marker from newsletter.ini, and replace placeholders in this marker.
     *
     * @return string
     */
    protected function getReadMarker() {
        if( $marker = $this->getIniValue( 'NewsletterTracking', 'ReadMarker' ) ) {
            return $this->replacePlaceholders( $marker );
        } else {
            return '';
        }
    }

    /**
     * Get newsletter.ini parameter value. Return false if doesn't exist.
     *
     * @return mixed
     */
    protected function getIniValue( $section, $param ) {
        if( $this->ini->hasVariable( $section, $param ) ) {
            return $this->ini->variable( $section, $param );
        } else {
            return false;
        }
    }

    // ######################################
    // Protected functions used in placeholders replacement.
    // ######################################

    /**
     * Return current OWNewsletterList alias, from url_alias of current newsletter
     * Used in placeholders replacement.
     *
     * @return string
     */
    protected function getListAlias() {
        if( $this->editionContentObject instanceof eZContentObject ) {
            $path_array = explode( '/', $this->editionContentObject->MainNode()->PathIdentificationString );
            if( count( $path_array ) > 1 ) {
                return $path_array[count( $path_array ) - 2];
            }
        }
        return '';
    }

    /**
     * Return current newsletter alias, from url_alias
     * Used in placeholders replacement.
     *
     * @return string
     */
    protected function getNlAlias() {
        if( $this->editionContentObject instanceof eZContentObject ) {
            $path_array = explode( '/', $this->editionContentObject->MainNode()->PathIdentificationString );
            if( count( $path_array ) > 0 ) {
                return $path_array[count( $path_array ) - 1];
            }
        }
        return '';
    }

    /**
     * Return ID of current newsletterUserObject
     * Used in placeholders replacement.
     *
     * @return string
     */
    protected function getUserID() {
        if( $this->newsletterUserObject instanceof OWNewsletterUser ) {
            return $this->newsletterUserObject->attribute( 'id' );
        }
        return '';
    }

    /**
     * Return value of current OWNewsletterEdition attribute, identified by $identifier
     * Used in placeholders replacement.
     *
     * @param string $identifier
     * @return string
     */
    protected function getNlAttribute( $identifier ) {
        if( $this->editionContentObject instanceof eZContentObject ) {
            $dataMap = $this->editionContentObject->dataMap();
            if( $dataMap[$identifier] ) {
                return $dataMap[$identifier]->DataText;
            }
        }
        return '';
    }

}