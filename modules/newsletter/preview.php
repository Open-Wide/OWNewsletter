<?php

$module = $Params["Module"];
$http = eZHTTPTool::instance();

$editionContentobjectId = $Params['EditionContentobjectId'];
$newsletterEdition = OWNewsletterEdition::fetchLastVersion( $editionContentobjectId );
if( !$newsletterEdition ) {
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}
if( $newsletterEdition->attribute( 'status' ) == OWNewsletterEdition::STATUS_DRAFT ) {
    $sending = OWNewsletterSending::create( $newsletterEdition );
} else {
    $sending = OWNewsletterSending::fetch( $editionContentobjectId );
}

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

$mailSubjectLabel = ezpI18n::tr( 'newsletter/preview', 'E-mail subject' );
$subjectStyle = 'style="background-color:#dddddd;border-color: #cccccc;border-width: 0 0 1px 0;border-style: solid;color:#333333;"';
$mailSubject = '<body${1}><!-- email subject preview start --><table width="100%" cellpadding="5" cellspacing="0" border="0" bgcolor="#dddddd" class="newsletter-skin-preview-email-subject" ' . $subjectStyle . '><tr><th width="1%" nowrap>' . $mailSubjectLabel . ':</th><td width="99%">' . $output['subject'] . '</td></tr></table></span><!-- email subject preview end -->';
$newsletterContent = preg_replace( "%<body([^>]*)>%", $mailSubject, $newsletterContent );


header( "Content-type: text/html; charset=utf-8" );
echo $newsletterContent;
eZExecution::cleanExit();
