#!/usr/bin/env php
<?php
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "OWNewsletter CreateOutput\n\n" .
        "\n" .
        "createoutput.php -s siteaccess" ),
        'use-session' => true,
        'use-modules' => true,
        'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[object_id:][object_version:][current_hostname:][www_dir:][skin_name:]", "", array(
    'object_id' => '--',
    'object_version' => '--',
    'current_hostname' => '--',
    'www_dir' => '--',
    'skin_name' => '--',
    ), false, array( 'siteaccess' => true,
    'user' => true ) );



$script->initialize();

// login as admin
// that template proecessing inlcude all images
$user = eZUser::fetchByName( 'admin' );

if( $user and $user->isEnabled() ) {
    $userID = $user->attribute( 'contentobject_id' );
    eZUser::setCurrentlyLoggedInUser( $user, $userID );
}

$objectId = 0;
$objectVersion = 0;
$currentHostName = '#current_hostname not set#';
$wwwDir = '';

$skinName = 'default';

if( $options['object_id'] ) {
    $objectId = (int) $options['object_id'];
}
if( $options['object_version'] ) {
    $objectVersion = (int) $options['object_version'];
}
if( $options['current_hostname'] ) {
    $currentHostName = $options['current_hostname'];
}
if( $options['www_dir'] ) {
    $wwwDir = $options['www_dir'];
}
if( $options['skin_name'] ) {
    $skinName = $options['skin_name'];
}

$ini = eZINI::instance( 'site.ini' );
$siteUrl = $ini->variable( 'SiteSettings', 'SiteURL' );
$locale = $ini->variable( 'RegionalSettings', 'Locale' );

$outputContent = '';

$contentObject = eZContentObjectVersion::fetchVersion( $objectVersion, $objectId );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'contentobject', $contentObject );

if( !is_object( $contentObject ) ) {
    $script->shutdown();
}

$contentType = 'text/html';
$newsletterEditionContent = array( 'html' => '', 'text' => '' );

$htmlMailImageInclude = 0;

$urlArray = getUrlArray( $siteUrl, $currentHostName, $wwwDir );

// textpart
/* $template = 'design:newsletter/skin/' . $skinName . '/outputformat/text.tpl';
  $content = $tpl->fetch( $template );
  $content = generateAbsoluteLinks( $content, $urlArray );
  $content = formatText( $content );
  $newsletterEditionContent['text'] = $content; */
// htmlpart
$templateName = $contentObject->contentObject()->ClassIdentifier;
$template = 'design:newsletter/skin/' . $skinName . '/output/' . $templateName . '.tpl';
$content = $tpl->fetch( $template );
$content = generateAbsoluteLinks( $content, $urlArray );
$newsletterEditionContent['html'] = compressHTML( $content );

$contentType = 'multipart/alternative';

if( OWNewsletterEdition::imageIncludeIsEnabled() ) {
    $htmlMailImageInclude = 1;
}

$subject = "newsletter subject $objectId";
if( $tpl->hasVariable( 'subject' ) ) {
    $subject = $tpl->variable( 'subject' );
}

$outputArray = array( 'contentobject_id' => $objectId,
    'contentobject_version' => $objectVersion,
    'content_type' => $contentType,
    'subject' => $subject,
    'body' => $newsletterEditionContent,
    'template' => $template,
    'template_validation' => $tpl->validateTemplateFile( $template, false ),
    'template_errors' => $tpl->errorLog(),
    'site_url' => $siteUrl,
    'locale' => $locale,
    'html_mail_image_include' => $htmlMailImageInclude
);

$outputArray = array_merge( $outputArray, $urlArray );

$cli->output( serialize( $outputArray ) );
$script->shutdown();

/**
 * $hostname = www.test.de
 *
 * @param string $string
 * @param array $urlArray
 * @return unknown_type
 */
function generateAbsoluteLinks( $string, $urlArray ) {

    /*
      $hostUrlEZ = 'http://' .$hostNameAndUri;
      $hostUrlRoot = 'http://' .$hostName;
     */

    $hostUrlEZ = $urlArray['ez_url'];
    $hostUrlRoot = $urlArray['ez_root'];

    $htmlPage = $string;
    $htmlPage = preg_replace( "/url:/", $hostUrlEZ, $htmlPage );
    $htmlPage = preg_replace( "/src=\"\/design/", 'src="' . $hostUrlRoot . '/design', $htmlPage );
    $htmlPage = preg_replace( "/src=\"\/extension/", 'src="' . $hostUrlRoot . '/extension', $htmlPage );
    $htmlPage = preg_replace( "/src=\"\/var/", 'src="' . $hostUrlRoot . '/var', $htmlPage );
    $htmlPage = preg_replace( "/href=\"\//", 'href="' . $hostUrlEZ . '/', $htmlPage );
    $htmlPage = preg_replace( "/Link: \"\//", 'Link: "' . $hostUrlEZ . '/', $htmlPage ); // proper Links in text version Change here from 285
    $htmlPage = preg_replace( "/url\(\"\//", 'url("' . $hostUrlRoot . '/', $htmlPage );
    $htmlPage = preg_replace( "/url\( \"\//", 'url( "' . $hostUrlRoot . '/', $htmlPage );
    $htmlPage = preg_replace( "/url\('\//", "url('" . $hostUrlRoot . '/', $htmlPage );
    $htmlPage = preg_replace( "/url\( '\//", "url( '" . $hostUrlRoot . '/', $htmlPage );

    return $htmlPage; // . '<!--'. print_r( $urlArray, true ).' -->';
}

function getUrlArray( $siteUrl, $currentHostName, $wwwDir ) {
    //1. case 1 host matching      www.example.com
    //2. case 2 host_uri matching  www.example.com/de
    //3. uri matching ip pased in subfolder   http://127.0.0.1/ezpublish/430/index.php/de

    $siteUrlWithoutHttp = $siteUrl;

    $UrlStartWith = 'http://';

    if( strpos( $siteUrl, 'http://' ) === 0 ) {
        $siteUrlWithoutHttp = substr( $siteUrl, 7, strlen( $siteUrl ) );
    }

    if( strpos( $siteUrl, 'https://' ) === 0 ) {
        $siteUrlWithoutHttp = substr( $siteUrl, 8, strlen( $siteUrl ) );
        $UrlStartWith = 'https://';
    }

    $siteUrlExplode = explode( '/', $siteUrlWithoutHttp );
    $siteDomainName = $siteUrlExplode[0];
    $hostName = $siteDomainName;

    $currentHostNameExplode = array_reverse( explode( '.', $currentHostName ) );
    $hostNameExplode = array_reverse( explode( '.', $siteDomainName ) );

    $hostNameAndUri = $siteUrlWithoutHttp;

    // testdomian   admin. [example.de] .jac430.fw.lokal - www. [example.de]
    if( isset( $currentHostNameExplode[4] ) && ( $currentHostNameExplode[4] == $hostNameExplode[1] ) && count( $hostNameExplode ) > 1 ) {
        $testHostName = $siteDomainName . '.' . $currentHostNameExplode[2] . '.' . $currentHostNameExplode[1] . '.' . $currentHostNameExplode[0];
        //$hostNameAndUri = $hostName . $siteUri;
        $hostNameAndUri = str_replace( $hostName, $testHostName, $hostNameAndUri );
        $hostName = $testHostName;
    }

    $urlRoot = $hostName;
    if( $wwwDir != '' ) {
        $urlRoot = $urlRoot . '/' . $wwwDir;
    }

    $ezUrl = $UrlStartWith . $hostNameAndUri;
    $ezRoot = $UrlStartWith . $urlRoot;

    return array( 'www_dir' => $wwwDir,
        'site_url' => $siteUrl,
        'current_host_name' => $currentHostName,
        'ez_url' => $ezUrl,
        'ez_root' => $ezRoot
    );
}

// TODO html to text parser define in tools class

/**
 *
 * @param unknown_type $content
 * @return unknown_type
 */
function formatText( $content ) {
    $content = html_entity_decode( $content, ENT_COMPAT, 'UTF-8' );

    // end get proper links in text version
    $content = str_replace( "\r", "\n", $content );

    $serachArray = array( '&nbsp;', "</p><p>", "<p>", '</p>', "\n\n\n\n<ol>", "</ol>\n\n\n\n",
        "\n\n\n\n<ul>", "</ul>\n\n\n\n", "\n\n<ol>", "</ol>\n\n", "\n\n<ul>", "</ul>\n\n",
        "<li>\n", '<li>', '</li>', "<hr>", "<br />\n", '<br />', '<br>', '<h1>', '</h1>',
        '<h2>', '</h2>', '<h3>', '</h3>', '<h4>', '</h4>', '<h5>', '</h5>' );
    $replaceArray = array( ' ', "[[[BR]]]\n", "\n", "\n", "[[[BR]]]", "[[[BR]]]", "[[[BR]]]",
        "[[[BR]]]", "[[[BR]]]", "[[[BR]]]", "[[[BR]]]", "[[[BR]]]", "<li>", '- ', "\n",
        "[[[HR]]]", "[[[BR]]]", "[[[BR]]]", '[[[BR]]]', "\n= ", " =\n", "\n== ", " ==\n",
        "\n=== ", " ===\n", "\n==== ", " ====\n", "\n===== ", " =====\n" );

    $content = str_replace( $serachArray, $replaceArray, $content );

    // get proper links in text version
    $content = formatTextLink( $content );

    $content = stripAttributes( $content );

    // preg_replace("/\n[^\w]*\n/","\n", $content);
    // turn returns to newlines:
    $content = str_replace( "\r", "\n", $content );
    // turn tabs to spaces:
    $content = str_replace( "\t", " ", $content );
    // next is searching for double spaces.
    /*  while (preg_match("/ /i", "$content"))
      {
      // replace them with single spaces:
      $content = str_replace(" ", " ", $content);
      } */

    // looks for spaces after a newline:
    while( preg_match( "/\n /", "$content" ) ) {
        // remove that space:
        $content = str_replace( "\n ", "\n", $content );
    }


    // look for two newlines:
    while( preg_match( "/\n\n/i", "$content" ) ) { // turn it to one newline
        $content = str_replace( "\n\n", "\n", $content );
    }



    // the \n now separates paragraphs; change \n to <p>:
    /* $content = "<p>" . str_replace("\n", "</p><p>", $content) . "</p>";
      $content = str_replace("<p></p>", "", $content); */
    // done!

    $serachArray = array( "\n= ",
        "\n== ",
        "\n=== ",
        "\n==== ",
        "\n===== ",
        "\n[[[HR]]]",
        "[[[BR]]]" );
    $replaceArray = array( "\n\n= ",
        "\n\n== ",
        "\n\n=== ",
        "\n\n==== ",
        "\n\n===== ",
        "\n--------------------------------------------------------------------------------",
        "\n" );

    $content = str_replace( $serachArray, $replaceArray, $content );

    return $content;
}

/**
 * strip all html tags
 * @param unknown_type $content
 * @return unknown_type
 */
function stripAttributes( $content ) {

    $pattern = array( '@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'   // Strip multi-line comments including CDATA
    );
    $content = preg_replace( $pattern, '', $content );
    return $content;
}

/**
 *
 * Format all a tags as text links
 * remove all achor tags
 * @param string $content
 * @param string $textLinkFormat here you can define how the html link ist formatted
 * the placeholders %url_link %url_text can be used in the string and will be replaced on demand
 * example: [ %url_text: %url_link ] => [ Newsletter: http://www.cjw-network.com ]
 */
function formatTextLink( $content, $textLinkFormat = "[ %url_text: %url_link ]" ) {
    //remove all ez anchors
    // <a name="eztoc598_1" id="eztoc598_1"></a> => ''
    // or <a name="bottom">my bottom anchor</a>
    $pattern = '#<a name="(.*?)".*?>(.*?)<?\/a>#is';

    //preg_match_all( $pattern, $content, $matchesAchors );
    //$content .= print_r( $matchesAchors , true );
    $content = preg_replace( $pattern, '', $content );

    // this tutorial helps me to create the regex http://www.phpmaniac.de/php_blog/php/html-seiten-crawlen-links-extrahieren/
    // find all links
    // $matches[0] => Array of original links     <a href="http://example.com" ...>This is the link text</a>
    // $matches[1] => Array with all links        http://example.com
    // $matches[2] => Array with all link texts   This is the link text
    $pattern = '/<a.*?href="(.*?)".*?>(.*?)<?\/a>/is';

    preg_match_all( $pattern, $content, $matches );

    for( $i = 0; $i < count( $matches[0] ); $i++ ) {
        $completeUrlString = $matches[0][$i];
        $urlLink = $matches[1][$i];
        $urlText = $matches[2][$i];
        $linkFormatted = str_replace( array( '%url_link', '%url_text' ), array( $urlLink,
            $urlText ), $textLinkFormat );
        $content = str_replace( $completeUrlString, $linkFormatted, $content );
    }
    return $content;
}

function compressHTML( $html ) {
    $search = array(
        '/\>[^\S\n ]+/s', // strip whitespaces after tags, except space
        '/[^\S\n ]+\</s', // strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1'
    );

    return preg_replace( $search, $replace, $html );
}
