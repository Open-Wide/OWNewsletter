<?php

/**
 * Cronjob newsletter_bounce_process.php
 */

OWScriptLogger::startLog( 'newsletter_bounce' );

$analyse = false;
$conds = array();

$newsletterINI = eZINI::instance('newsletter.ini');
$fetchLimit = $newsletterINI->variable('BounceSettings', 'CronjobFetchLimitdValue');

// Récupération des emails
$collectMailResult = OWNewsletterMailbox::collectMailsFromActiveMailboxes($fetchLimit);

foreach($collectMailResult as $mailbox_id => $bounces_status) {
  if( is_array($collectMailResult[$mailbox_id]) ) {
    OWScriptLogger::logNotice( "Nombre des emails ajouté : " . count($collectMailResult[$mailbox_id]['added']), 'newsletter_bounce' );
    OWScriptLogger::logNotice( "Nombre des emails déjà existant : " . count($collectMailResult[$mailbox_id]['exists']), 'newsletter_bounce' );
    OWScriptLogger::logNotice( "Nombre des emails en échec : " . count($collectMailResult[$mailbox_id]['failed']), 'newsletter_bounce' );
    $analyse = true;
  } else {
    OWScriptLogger::logError( "Échec de la connexion", 'newsletter_bounce' );
   }
}

// Analyse des emails
if($analyse) {
  $conds['status'] = OWNewsletterUser::STATUS_BOUNCED_SOFT;
  $nbrBounceInitial = OWNewsletterUser::countList( $conds );

  $parseResultArray = OWNewsletterBounce::parseActiveItems();
  OWScriptLogger::logNotice( "E-mails analysés : " . count($parseResultArray), 'newsletter_bounce' );

  $nbrBounceFin = OWNewsletterUser::countList( $conds );

  OWScriptLogger::logNotice( "Nombre des abonnés ajouter à la list bounced  : " . ($nbrBounceFin - $nbrBounceInitial), 'newsletter_bounce' );
  OWScriptLogger::logNotice( "Nombre total des abonnés bounced  : " . $nbrBounceFin, 'newsletter_bounce' );
}