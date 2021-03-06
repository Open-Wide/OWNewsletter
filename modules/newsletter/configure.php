<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();

$newsletterUser = OWNewsletterUser::fetchByHash( $Params['Hash'] );

if( !$newsletterUser instanceof OWNewsletterUser || $newsletterUser->isOnBlacklist() ) {
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
switch( $newsletterUser->attribute( 'status' ) ) {
    case OWNewsletterUser::STATUS_BLACKLISTED :
    case OWNewsletterUser::STATUS_REMOVED_ADMIN :
    case OWNewsletterUser::STATUS_REMOVED_SELF :
    case OWNewsletterUser::STATUS_PENDING : 
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    /*case OWNewsletterUser::STATUS_PENDING:
        $redirectUrlConfirmation = 'newsletter/confirmation/' . $Params['Hash'];
        $module->redirectTo( $redirectUrlConfirmation );
        break;*/
}

/* if the status progresses from Pending to confirm then displays the validation page */

/* Retrieval of cancel and success redirect URLs */
$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/configure/' . $Params['Hash'];
if( $module->hasActionParameter( 'RedirectUrlActionCancel' ) ) {
    $redirectUrlCancel = $module->actionParameter( 'RedirectUrlActionCancel' );
}
if( $module->hasActionParameter( 'RedirectUrlActionSuccess' ) ) {
    $redirectUrlSuccess = $module->actionParameter( 'RedirectUrlActionSuccess' );
}
$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_success', $redirectUrlSuccess );


/* If press Cancel button */
if( $module->isCurrentAction( 'Cancel' ) ) {
    $module->redirectTo( $redirectUrlCancel );
}

if( $module->isCurrentAction( 'Configure' ) && $module->hasActionParameter( 'NewsletterUser' ) ) {
    $newsletterUserRow = array(
        'id' => $newsletterUser->attribute( 'id' ),
        'email' => '',
        'salutation' => '',
        'first_name' => '',
        'last_name' => '',
        'subscription_list' => array(),
        'mailing_list' => array()
    );
    $newsletterUserRow = array_merge( $newsletterUserRow, $module->actionParameter( 'NewsletterUser' ) );
    $warningMessages = array();
    $successMessage = '';
    if( empty( $newsletterUserRow['email'] ) ) {
        $warningMessages[] = array(
            'field_key' => ezpI18n::tr( 'newsletter/configure', 'E-mail' ),
            'message' => ezpI18n::tr( 'newsletter/warning_message', 'You must provide a valid email address.' ) );
    }
    if( empty( $newsletterUserRow['subscription_list'] ) ) {
        $warningMessages[] = array(
            'field_key' => ezpI18n::tr( 'newsletter/configure', 'Newsletter' ),
            'message' => ezpI18n::tr( 'newsletter/warning_message', 'You must select at least one newsletter.' ) );
    }
    if( empty( $warningMessages ) ) {
        try {
            $newsletterUser = OWNewsletterUser::createOrUpdate( $newsletterUserRow, 'configure' );
        } catch( Exception $e ) {
            $warningMessages[] = array(
                'field_key' => ezpI18n::tr( 'newsletter/subscribe', 'E-mail' ),
                'message' => ezpI18n::tr( 'newsletter/warning_message', $e->getMessage() )
            );
        }
        $validateAdditionalData = $newsletterUser->validateAdditionalData( $newsletterUserRow['additional_data'] );
        if( $validateAdditionalData !== false ) {
            $attributeWarningList = array_merge( $attributeWarningList, $validateAdditionalData['warning_field'] );
            $warningMessages = array_merge( $warningList, $validateAdditionalData['warning_message'] );
        }
        $newsletterUser->setAdditionalData( $newsletterUserRow['additional_data'] );
        if( !empty( $newsletterUserRow['subscription_list'] ) ) {
            $unsubscribeList = array_diff( $newsletterUser->attribute('active_mailing_list_contentobject_ids'), $newsletterUserRow['subscription_list'] );
            foreach( $unsubscribeList as $unsubscribe ) {
                try {
                    $newsletterUser->unsubscribeFrom( $unsubscribe );
                } catch( Exception $e ) {
                    $warningMessages[] = array(
                        'field_key' => ezpI18n::tr( 'newsletter/subscribe', 'Newsletter' ),
                        'message' => ezpI18n::tr( 'newsletter/warning_message', $e->getMessage() )
                    );
                }
            }
            foreach( $newsletterUserRow['subscription_list'] as $subscription ) {
                try {
                    $newsletterUser->subscribeTo( $subscription, OWNewsletterSubscription::STATUS_PENDING, 'configure' );
                } catch( Exception $e ) {
                    $warningMessages[] = array(
                        'field_key' => ezpI18n::tr( 'newsletter/subscribe', 'Newsletter' ),
                        'message' => ezpI18n::tr( 'newsletter/warning_message', $e->getMessage() )
                    );
                }
            }
        } else {
            $warningMessages[] = array(
                'field_key' => ezpI18n::tr( 'newsletter/subscribe', 'Newsletter' ),
                'message' => ezpI18n::tr( 'newsletter/warning_message', 'You must select at least one newsletter.' ) );
        }

        if (empty($successMessage)) {
            $successMessage = ezpI18n::tr( 'newsletter/configure', 'Your changes have been saved.' );
        }
    }
}
if( !empty( $warningMessages ) ) {
    $tpl->setVariable( 'warning_array', $warningMessages );
}
if( !empty( $successMessage ) ) {
    $tpl->setVariable( 'success_message', $successMessage );
}
$tpl->setVariable( 'newsletter_user', $newsletterUser );
$tpl->setVariable( 'available_salutation_array', OWNewsletterUser::getAvailablesSalutationsFromIni() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:newsletter/configure/form.tpl' );
$Result['path'] = array(
    array(
        'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
    array(
        'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Configure' ) ) );
