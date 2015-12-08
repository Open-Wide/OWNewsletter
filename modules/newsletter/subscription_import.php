<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();

/* Initilize module result */
$Result = array();
$Result['path'] = array(
    array(
        'url' => false,
        'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
    array(
        'url' => false,
        'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Import subscriptions' ) ) );


$subscriptionStatus = array( OWNewsletterSubscription::STATUS_APPROVED );
$subscriptionFields = array( "email" );
$columnDelimiter = ";";
$mailingListID = $Params['mailingListID'];


if( empty( $mailingListID ) ) {
    $tpl->setVariable( 'warning', ezpI18n::tr( 'newsletter/warning_message', 'Mailing list is missing.' ) );
} else {
    $ini  = eZINI::instance( 'newsletter.ini' );
    $contentObject = eZContentObject::fetch( $mailingListID );
    if( $contentObject instanceof eZContentObject ) {
        $tpl->setVariable( 'mailing_list', $contentObject );
        $node = $contentObject->attribute( 'main_node' );
        $Result['path'][] = array(
            'url' => false,
            'text' => $node->attribute( 'name' ) );
        $redirectUrlSuccess = 'newsletter/subscription_import/' . $mailingListID;
        if( $module->hasActionParameter( 'ColumnDelimiter' ) ) {
            $columnDelimiter = $module->actionParameter( 'ColumnDelimiter' );
        }
        $tpl->setVariable( 'column_delimiter', $columnDelimiter );
        $defaultFields = array( 'email', 'first_name', 'last_name', 'salutation');
        $additionalFields = array();
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
        $allFields = array_merge($defaultFields, $additionalFields);
        $tpl->setVariable( 'default_fields', $defaultFields );
        if( !$module->hasActionParameter( 'FirstLineIsColumnHeadings' ) ) {
            $fileHeaders = $allFields;
            $tpl->setVariable( 'first_line_is_column_headings', false );
        } else {
            $hasFileHeaders = true;
            $tpl->setVariable( 'first_line_is_column_headings', true );
        }
        $tpl->setVariable( 'all_fields', $allFields );
        if( $module->isCurrentAction( 'Preview' ) ) {
            if( eZHTTPFile::canFetch( 'UploadFile' ) ) {
                $binaryFile = eZHTTPFile::fetch( 'UploadFile' );
                $suffix = pathinfo( $binaryFile->attribute( 'original_filename' ), PATHINFO_EXTENSION );
                $binaryFile->store( 'ow_newsletter', $suffix );
                $tpl->setVariable( 'upload_file', $binaryFile->attribute( 'filename' ) );
                ini_set( 'auto_detect_line_endings', TRUE );
                $handle = fopen( realpath( $binaryFile->attribute( 'filename' ) ), 'r' );
                $rowCount = 0;
                $preview = array();
                while( ($row = fgetcsv( $handle, 0, $columnDelimiter ) ) !== FALSE ) {
                    if( !isset( $fileHeaders ) ) {
                        $fileHeaders = $row;
                    } else {
                        $rowCount++;
                        $rowPreview = array(
                            'row_number' => $rowCount,
                        );
                        foreach($allFields as $fields) {
                            $rowPreview[$fields] = false;
                        }
                        foreach( $row as $index => $field ) {
                            $fieldIdentifier = $fileHeaders[$index];
                            if(in_array($fieldIdentifier, $allFields)) {
                                $rowPreview[$fieldIdentifier] = $field;
                            }
                        }
                        $preview[] = $rowPreview;
                    }
                }
                $tpl->setVariable( 'preview', $preview );
            } else {
                $tpl->setVariable( 'warning', ezpI18n::tr( 'newsletter/warning_message', 'File is missing.' ) );
            }
        } elseif( $module->isCurrentAction( 'Import' ) ) {
            if( $module->hasActionParameter( 'UploadFile' ) ) {
                
                $binaryFile = $module->actionParameter( 'UploadFile' );
               
                /* add import line for cron tab */
                $dataImport =   array(
                            'file'=>$binaryFile,
                            'column_delimiter'=> $columnDelimiter,
                            'file_header' => isset($hasFileHeaders)?1:0,
                            'mailing_list_id'=>$mailingListID,
                            );

                
                $rowPending = array(
                    'action'        => "ownewsletter_import",
                    'created'       => time(),
                    'param'         => serialize($dataImport)
                );

                $pendingItem = new eZPendingActions( $rowPending );
                $pendingItem->store();

                $tpl->setVariable( 'log_cron', 1 );               

            }
        }
    } else {
        $tpl->setVariable( 'warning', ezpI18n::tr( 'newsletter/warning_message', 'Mailing list not found.' ) );
    }
}

/* Retrieval of cancal and success redirect URLs */
if( $module->hasActionParameter( 'RedirectUrlActionSuccess' ) ) {
    $redirectUrlSuccess = $module->actionParameter( 'RedirectUrlActionSuccess' );
}
$tpl->setVariable( 'redirect_url_action_success', $redirectUrlSuccess );

$userDefinition = OWNewsletterUser::definition();
$fieldList = array_merge( array_keys( $userDefinition['fields'] ), array_keys( $userDefinition['function_attributes'] ) );
sort( $fieldList );
$tpl->setVariable( 'available_field_list', $fieldList );
$tpl->setVariable( 'column_delimiter', $columnDelimiter );
$tpl->setVariable( 'selected_status_list', $subscriptionStatus );
$tpl->setVariable( 'selected_field_list', $subscriptionFields );
$Result['content'] = $tpl->fetch( "design:newsletter/subscriptions/import.tpl" );

