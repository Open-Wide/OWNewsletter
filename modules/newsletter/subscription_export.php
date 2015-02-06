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
        'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Export subscriptions' ) ) );

$subscriptionStatus = array( 'pending' );
$userStatus = array( 'confirmed' );

$subscriptionFields = array( "email" );
$columnDelimiter = ";";
$mailingListID = $Params['mailingListID'];
if( empty( $mailingListID ) ) {
    $tpl->setVariable( 'warning', ezpI18n::tr( 'newsletter/warning_message', 'Mailing list is missing.' ) );
} else {
    $contentObject = eZContentObject::fetch( $mailingListID );
    if( $contentObject instanceof eZContentObject ) {
        $tpl->setVariable( 'mailing_list', $contentObject );
        $node = $contentObject->attribute( 'main_node' );
        $Result['path'][] = array(
            'url' => false,
            'text' => $node->attribute( 'name' ) );
        $redirectUrlSuccess = 'newsletter/subscription_export/' . $mailingListID;
        if( $module->isCurrentAction( 'Export' ) ) {

            $exportConds['subscription']['mailing_list_contentobject_id'] = $mailingListID;

            if( $module->hasActionParameter( 'SubscriptionStatus' ) ) {

                $subscriptionStatus = $module->actionParameter( 'SubscriptionStatus' );
                if( is_array( $subscriptionStatus ) && isset( $subscriptionStatus[0] ) ) {
                    $filterStatus = $subscriptionStatus[0];
                } else {
                    $filterStatus = 'approved';
                }

                switch( $filterStatus ) {
                    case 'pending':
                        $exportConds['status'] = OWNewsletterUser::STATUS_PENDING;
                        break;
                    case 'bounced':
                    case 'removed':
                    case 'blacklisted':
                        $userStatus = OWNewsletterFunctionCollection::getUserStatus( $filterStatus );
                        $exportConds['status'] = is_array( $userStatus ) ? array( $userStatus ) : (int) $userStatus;
                        break;
                    case 'confirmed':
                        $exportConds['status'] = OWNewsletterUser::STATUS_CONFIRMED;
                        $exportConds['subscription']['status'] = OWNewsletterSubscription::STATUS_PENDING;
                        break;
                    case 'approved':
                        $exportConds['status'] = OWNewsletterUser::STATUS_CONFIRMED;
                        $exportConds['subscription']['status'] = OWNewsletterSubscription::STATUS_APPROVED;
                        break;
                    case 'inactived':
                        $exportConds['status'] = OWNewsletterUser::STATUS_CONFIRMED;
                        $exportConds['subscription']['status'] = OWNewsletterSubscription::STATUS_INACTIVED;
                        break;
                }
            }
            if( $module->hasActionParameter( 'SubscriptionFields' ) ) {
                $subscriptionFields = array_merge( $subscriptionFields, $module->actionParameter( 'SubscriptionFields' ) );
            }
            if( $module->hasActionParameter( 'ColumnDelimiter' ) ) {
                $columnDelimiter = $module->actionParameter( 'ColumnDelimiter' );
            }

            $exportUserList = OWNewsletterUser::fetchListWithSubscription( $exportConds );

            header( 'Content-Type: text/csv' );
            header( 'Content-Disposition: attachment;filename=subscriptions.csv' );
            $fp = fopen( 'php://output', 'w' );
            fputcsv( $fp, $subscriptionFields, $columnDelimiter );
            foreach( $exportUserList as $exportUser ) {
                $row = array();
                $userAdditionalData = $exportUser->attribute( 'additional_data' );
                foreach( $subscriptionFields as $field ) {
                    if( strpos( $field, 'additional_data_' ) === 0 ) {
                        $fieldIdentifier = substr( $field, 16 );
                        if( isset( $userAdditionalData[$fieldIdentifier] ) ) {
                            if( is_array( $userAdditionalData[$fieldIdentifier] ) ) {
                                $row[] = implode( ';', $userAdditionalData[$fieldIdentifier] );
                            } else {
                                $row[] = $userAdditionalData[$fieldIdentifier];
                            }
                        } else {
                            $row[] = 'n/a';
                        }
                    } elseif( $exportUser->hasAttribute( $field ) ) {
                        switch( $field ) {
                            case 'created':
                            case 'modified':
                            case 'confirmed':
                            case 'removed':
                            case 'bounced':
                            case 'blacklisted':
                                if( $exportUser->attribute( $field ) != 0 ) {
                                    $row[] = strftime( '%Y-%m-%d', $exportUser->attribute( $field ) );
                                } else {
                                    $row[] = 'n/a';
                                }

                                break;
                            case 'creator':
                            case 'modifier':
                            case 'ez_user':
                                $value = $exportUser->attribute( $field );
                                if( $value instanceof eZContentObject ) {
                                    $row[] = $value->attribute( 'name' );
                                } else {
                                    $row[] = 'n/a';
                                }
                                break;
                            case 'is_confirmed':
                            case 'is_removed_self':
                            case 'is_removed':
                            case 'is_on_blacklist':
                                $row[] = $exportUser->attribute( $field ) ? 'Yes' : 'No';
                                break;
                            case 'subscription_list':
                            case 'active_subscriptions':
                            case 'approved_subscriptions':
                            case 'additional_fields':
                            case 'additional_data':
                                $row[] = 'n/a';
                                break;
                            case 'approved_mailing_lists':
                                $value = $exportUser->attribute( $field );
                                $valueArray = array();
                                foreach( $value as $item ) {
                                    $valueArray[] = $item->attribute( 'name' );
                                }
                                $row[] = implode( ' - ', $valueArray );
                                break;
                            default:
                                $row[] = $exportUser->attribute( $field );
                                break;
                        }
                    } else {
                        $row[] = 'n/a';
                    }
                }
                fputcsv( $fp, $row, $columnDelimiter );
            }
            fclose( $fp );
            eZExecution::cleanExit();
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
$tmpUser = new OWNewsletterUser();
$additionalFields = array_keys( $tmpUser->attribute( 'additional_fields' ) );
$func = function($value) {
    return 'additional_data_' . $value;
};
$additionalFields = array_map( $func, $additionalFields );
$fieldList = array_merge( array_keys( $userDefinition['fields'] ), array_keys( $userDefinition['function_attributes'] ), $additionalFields );
sort( $fieldList );
$tpl->setVariable( 'available_field_list', $fieldList );
$tpl->setVariable( 'column_delimiter', $columnDelimiter );
$tpl->setVariable( 'selected_status_list', $subscriptionStatus );
$tpl->setVariable( 'selected_field_list', $subscriptionFields );
$Result['content'] = $tpl->fetch( "design:newsletter/subscriptions/export.tpl" );

