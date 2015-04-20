<?php

$module = $Params['Module'];
$tpl = eZTemplate::factory();
$http = eZHTTPTool::instance();

/* Retrieval of cancel and success redirect URLs */
$redirectUrlCancel = $redirectUrlSuccess = 'newsletter/resendconfigurelink';
if( $module->hasActionParameter( 'RedirectUrlActionCancel' ) ) {
    $redirectUrlCancel = $module->actionParameter( 'RedirectUrlActionCancel' );
}
if( $module->hasActionParameter( 'RedirectUrlActionSuccess' ) ) {
    $redirectUrlSuccess = $module->actionParameter( 'RedirectUrlActionSuccess' );
}
$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_success', $redirectUrlSuccess );

$newsletterIni = eZINI::instance( 'newsletter.ini' );

$tmpUser = new OWNewsletterUser();
$email = $module->hasActionParameter( 'Email' )  ? $module->actionParameter( 'Email' ) : false;
$newsletterUser = OWNewsletterUser::fetchByEmail($email);

$template = 'design:newsletter/subscribe/form.tpl';

if( $module->isCurrentAction( 'ResendConfigureLink' ) ) {
    if( $newsletterUser ) {
        $newsletterUser->sendConfigureLinkMail();
        $tpl->setVariable( 'existing_newsletter_user', $newsletterUser );
        $template = 'design:newsletter/resendconfigurelink/success.tpl';
    }
}
$tpl->setVariable( 'newsletter_user', $newsletterUserRow );
$tpl->setVariable( 'available_salutation_array', OWNewsletterUser::getAvailablesSalutationsFromIni() );

$Result = array();
$Result['content'] = $tpl->fetch( $template );
$Result['path'] = array(
    array(
        'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
    array(
        'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Subscribe' ) ) );
