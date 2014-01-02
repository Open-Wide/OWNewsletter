<?php

class ownewsletterInfo
{
    // set manually - is used in email header, and in file header @version
    const SOFTWARE_VERSION = '0.1.0';

    static function info()
    {
        return array( 'Name'             => 'OWNewsletter - Multi Channel Marketing',
                      'Version'          => self::SOFTWARE_VERSION,
                      'eZ version'       => '4.x',
                      'Copyright'        => '',
                      'License'          => 'GNU General Public License v2.0',
                      'More Information' => ''
                    );
    }

    /**
     * get some additional infos about the newsletter
     * for future use
     */
    static function packageInfo()
    {
        $infoArray = array();
        $infoArray[ 'release_version' ] = '//release_version//';

        // is set when building the package
        $infoArray[ 'release_svn_revision' ] = '//release_svn_revision//';
        return $infoArray;
    }
}
?>
