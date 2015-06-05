<?php

/**
 * Cronjob newsletter_mailqueue_create.php
 */
// Get all wait for process sending
// On requête pour voir les fichiers à importer.
// On boucle sur la liste.
// On charge le fichier.
// On boucle sur le fichier.
// Si erreur on recup les erreurs.
// On marque l'import comme fait et on précise les erreurs si erreur il y a. 


$listImport = OWNewsletterImport::fetchByProcessed();

foreach ($listImport as $importAsk) {

    /* @var $importAsk OWNewsletterImport */
    $binaryFile =  $importAsk->attribute('file');
    $mailingListID = $importAsk->attribute('mailing_list_id');
    $columnDelimiter = $importAsk->attribute('column_delimiter');
    $fileHeaders = $importAsk->attribute('file_header');
    
    $error = null;
    $log = "";

    if(!is_file($binaryFile)){
        $error = "Le fichier $binaryFile n'existe pas";
    }elseif(empty($mailingListID) || intval($mailingListID)==0 ){
        $error = "La mailing list n'est pas renseignée";
    }elseif(($mailingList = OWNewsletterMailingList::fetchLastVersion( $mailingListID ))){
        $error = "La mailing list $mailingListID n'existe pas";
    }else{
        $log = ImportBinaryFile($binaryFile, $mailingListID, $columnDelimiter, $fileHeaders);
    }

    $importAsk->setProcessed();
    
    if($error){
        $importAsk->setError(1,$error);
    }else{
        $importAsk->setError(0,$log);
    }
    
    // envoie de mail
    
    $mailingList->attribute()
    
    

}

function isMailingList($mailingListID){
    $mailingList = OWNewsletterMailingList::fetchLastVersion( $mailingListID );
    if(is_object($mailingList)){
        return true;
    }else{
        return false;
    }
}

function ImportFile($binaryFile, $mailingListID,$columnDelimiter,$fileHeaders) {


    ini_set('auto_detect_line_endings', TRUE);
    $handle = fopen($binaryFile, 'r');
    OWScriptLogger::startLog('subscription_import');
    $rowCount = 0;
    $createdCount = 0;
    $subscriptionCount = 0;
    
    $additionalFieldsOptions = array();
    if($ini->hasVariable('NewsletterUserSettings', 'AdditionalFields')) {
        $additionalFields = $ini->variable('NewsletterUserSettings', 'AdditionalFields');
        $tpl->setVariable( 'additional_fields', $additionalFields );
        foreach($additionalFields as $fieldIdentifier) {
            if($ini->hasVariable('AdditionalField_' . $fieldIdentifier, 'SelectOptions')) {
                $additionalFieldsOptions[$fieldIdentifier] = $ini->variable('AdditionalField_' . $fieldIdentifier, 'SelectOptions');
            }
        }
    }    
    
    while (($row = fgetcsv($handle, 0, $columnDelimiter) ) !== FALSE) {
        if (!isset($fileHeaders)) {
            $fileHeaders = $row;
        } else {
            $rowCount++;
            $userInfo = array('status' => OWNewsletterUser::STATUS_CONFIRMED);
            $userAdditionalFields = array();
            foreach ($row as $index => $field) {
                $fieldIdentifier = $fileHeaders[$index];
                if (in_array($fieldIdentifier, $defaultFields)) {
                    $userInfo[$fieldIdentifier] = $field;
                } elseif (in_array($fieldIdentifier, $additionalFields)) {
                    if (array_key_exists($fieldIdentifier, $additionalFieldsOptions)) {
                        if (( $key = array_search($field, $additionalFieldsOptions[$fieldIdentifier])) !== false) {
                            $userAdditionalFields[$fieldIdentifier] = $key;
                        } elseif (isset($additionalFieldsOptions[$fieldIdentifier][$field])) {
                            $userAdditionalFields[$fieldIdentifier] = $field;
                        }
                    } else {
                        $userAdditionalFields[$fieldIdentifier] = $field;
                    }
                }
            }
            if (isset($userInfo['email']) && !empty($userInfo['email']) && ezcMailTools::validateEmailAddress($userInfo['email'])) {
                $user = OWNewsletterUser::fetchByEmail($userInfo['email']);
                if (!$user instanceof OWNewsletterUser) {
                    $user = OWNewsletterUser::createOrUpdate($userInfo, 'import');
                    if (count($userAdditionalFields) > 0) {
                        $result = $user->validateAdditionalData($userAdditionalFields);
                        if ($result !== false) {
                            OWScriptLogger::logError("Row #$rowCount : failed to import in additional fields, " . implode($result['warning_message'], ' '), 'process_row');
                        }
                        $user->setAttribute('serialized_data', serialize($userAdditionalFields));
                        $user->store();
                    }
                    OWScriptLogger::logNotice("Row #$rowCount : user created (" . $userInfo['email'] . ")", 'process_row');
                    $createdCount++;
                }
                $user->subscribeTo($mailingListID, OWNewsletterSubscription::STATUS_APPROVED, 'import');
                OWScriptLogger::logNotice("Row #$rowCount : user subscribe to the mailing list", 'process_row');
                $subscriptionCount++;
            } else {
                OWScriptLogger::logError("Row #$rowCount : failed to import, e-mail is missing or invalid", 'process_row');
            }
        }
    }
    OWScriptLogger::logNotice("$rowCount rows processed" . PHP_EOL . "$subscriptionCount subscriptions created or updated" . PHP_EOL . "$createdCount users created", 'treatment_completed');
    $logger = OWScriptLogger::instance();

    ini_set('auto_detect_line_endings', FALSE);
    
    return 'owscriptlogger/logs/' . $logger->attribute( 'id' );
}
