<?php

$module = $Params["Module"];
$http = eZHTTPTool::instance();

$editionContentobjectId = $Params['EditionContentobjectId'];
$newsletterEdition = OWNewsletterEdition::fetchLastVersion( $editionContentobjectId );
if( !$newsletterEdition ) {
    return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

if( $newsletterEdition->attribute( 'status' ) != OWNewsletterEdition::STATUS_ARCHIVE ) {
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
$sending = OWNewsletterSending::fetch( $editionContentobjectId );

$newsletterContent = '';
$output = $sending->attribute( 'output' );

if( $output['content_type'] == 'text/html' ) {
    $newsletterContent .= $output['body']['html'];
} elseif( $output['content_type'] == 'multipart/alternative' ) {
    $newsletterContent .= $output['body']['html'];
    $textContent = "<hr /><pre>" . $output['body']['text'] . "</pre></body>";
    $newsletterContent = str_replace( '</body>', $textContent, $newsletterContent );
} elseif( $output['content_type'] == 'text/plain' ) {
    if( $showRawContent === false ) {
        $newsletterContent .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>newsletter - outputformat - text</title></head><body>
<pre>' . $output['body']['text'] . '</pre></body></html>';
    } else {
        $newsletterContent = $output['body']['text'];
    }
} else {
    return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

header( "Content-type: text/html; charset=utf-8" );
echo $newsletterContent;
eZExecution::cleanExit();
