{'Hello %name'|i18n( 'newsletter/resendconfigurelink/mail',,hash('%name', $newsletter_user.name ))},

{'To edit your subscription, please visit'|i18n( 'newsletter/resendconfigurelink/mail')} {'this link'|i18n( 'newsletter/resendconfigurelink/mail')}:
{concat('/newsletter/configure/', $newsletter_user.hash)|ezurl('no', 'full')}
