<?php

/**
 * Cronjob newsletter_import.php
 */
$actions = eZPendingActions::fetchByAction('ownewsletter_import');
/* $var $action eZPendingActions */
foreach ($actions as $action) {

    OWScriptLogger::startLog('subscription_import');
    $params = unserialize($action->attribute('param'));

    // On test si le data existe sionon on le créé
    $eZSiteData = eZSiteData::fetchByName('ownewsletter');


    if (!$eZSiteData) {



        $params['start'] = time();

        $eZSiteData = new eZSiteData(array('name' => 'ownewsletter', 'value' => serialize($params)));
        $eZSiteData->store();

        $binaryFile = $params['file'];
        $mailingListID = $params['mailing_list_id'];
        $columnDelimiter = $params['column_delimiter'];
        $fileHeaders = $params['file_header'];

        OWScriptLogger::logNotice("Start import : " . $binaryFile, 'process_row');

        $error = false;
        $log = "";

        if (!is_file($binaryFile)) {
            OWScriptLogger::logError("The file does not exist : " . $binaryFile, 'process_row');
            $error = true;
        } elseif (empty($mailingListID) || intval($mailingListID) == 0) {
            OWScriptLogger::logError("The mailing list id is not defined : " . $mailingListID, 'process_row');
            $error = true;
        } elseif (!($mailingList = OWNewsletterMailingList::fetchLastVersion($mailingListID))) {
            OWScriptLogger::logError("The mailing list does not exist : ", 'process_row');
            $error = true;
        } else {
            $log = ImportBinaryFile($binaryFile, $mailingListID, $columnDelimiter, $fileHeaders);
        }
        $action->remove();
        $eZSiteData->remove();


        // ENVOIE MAIL
        // creation contenu mail


        $newsletterINI = eZINI::instance('newsletter.ini');
        $INI = eZINI::instance();

        OWScriptLogger::logNotice("Start send email import.", 'process_row');
        try {

            $importEmail = $newsletterINI->variable('NewsletterMailSettings', 'ImportEmail');
            $importName = $newsletterINI->variable('NewsletterMailSettings', 'ImportName');
            $senderEmail = $newsletterINI->variable('NewsletterMailSettings', 'SenderEmail');
            $senderName = $newsletterINI->variable('NewsletterMailSettings', 'SenderName');

            $mail = new eZMail();
            $logger = OWScriptLogger::instance();

            $Tpl = eZTemplate::factory();
            $log = 'http://' . $INI->variable('SiteSettings', 'SiteURL') . '/owscriptlogger/logs/' . $logger->attribute('id');
            $Tpl->setVariable('log', $log);
            $templateResult = $Tpl->fetch('design:mail/import.tpl');

            $subject = ezpI18n::tr('email/import', "End of your email import.");

            if (!empty($senderName)) {
                $mail->setSender($senderEmail, $senderName);
            } else {
                $mail->setSender($senderEmail);
            }

            if (!empty($senderName)) {
                $mail->setReceiver($importEmail, $importName);
            } else {
                $mail->setReceiver($importEmail);
            }

            $mail->setReceiver($importEmail);
            $mail->setSubject($subject);
            $mail->setBody($templateResult);
            $mail->setContentType('text/html');

            $mailResult = eZMailTransport::send($mail);
        } catch (Exception $ex) {
            OWScriptLogger::logError("The mailing of end import is failed.", 'process_row');
        }
        
        OWScriptLogger::logNotice("End send email import.", 'process_row');
        
    } else {
        OWScriptLogger::logError("A process is already active.", 'process_active');
    }
}

function ImportBinaryFile($binaryFile, $mailingListID, $columnDelimiter, $isFileHeaders) {

    $ini = eZINI::instance('newsletter.ini');
    ini_set('auto_detect_line_endings', TRUE);
    $handle = fopen($binaryFile, 'r');

    $rowCount = 0;
    $createdCount = 0;
    $subscriptionCount = 0;

    $defaultFields = array('email', 'first_name', 'last_name', 'salutation');
    $additionalFields = array();
    $additionalFieldsOptions = array();

    if ($ini->hasVariable('NewsletterUserSettings', 'AdditionalFields')) {
        $additionalFields = $ini->variable('NewsletterUserSettings', 'AdditionalFields');
        foreach ($additionalFields as $fieldIdentifier) {
            if ($ini->hasVariable('AdditionalField_' . $fieldIdentifier, 'SelectOptions')) {
                $additionalFieldsOptions[$fieldIdentifier] = $ini->variable('AdditionalField_' . $fieldIdentifier, 'SelectOptions');
            }
        }
    }


    if (!$isFileHeaders) {
        $fileHeaders = array_merge($defaultFields, $additionalFields);
        ;
    } else {
        $hasFileHeaders = true;
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
                OWScriptLogger::logError("Row #$rowCount : failed to import, e-mail is missing or invalid (" . $userInfo['email'] . ")", 'process_row');
            }
        }
    }
    OWScriptLogger::logNotice("$rowCount rows processed" . PHP_EOL . "$subscriptionCount subscriptions created or updated" . PHP_EOL . "$createdCount users created", 'treatment_completed');
    $logger = OWScriptLogger::instance();

    ini_set('auto_detect_line_endings', FALSE);

    return 'owscriptlogger/logs/' . $logger->attribute('id');
}
