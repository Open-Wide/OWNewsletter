{'Hello %name'|i18n( 'newsletter/confirmation/mail',,hash('%name', $newsletter_user.name ))},

{'To activate or edit your subscription, please visit'|i18n( 'newsletter/confirmation/mail')} {'this link'|i18n( 'newsletter/confirmation/mail')}:
{concat('/newsletter/configure/', $newsletter_user.hash)|ezurl('no', 'full')}
