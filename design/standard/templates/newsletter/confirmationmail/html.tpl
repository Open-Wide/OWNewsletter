<h1>{'Hello %name'|i18n( 'newsletter/confirmation/mail',,hash('%name', $newsletter_user.name ))},</h1>

<p>
    {'To activate or edit your subscription, please visit'|i18n( 'newsletter/confirmation/mail')}
    <a href="{concat('/newsletter/confirmation/', $newsletter_user.hash)|ezurl('no', 'full')}">{'this link'|i18n( 'newsletter/confirmation/mail')}.</a>
</p>
