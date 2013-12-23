{def $newsletter_list_content = $attribute.content}

<div class="block float-break">
	<div class="element">
		<label>{'Show in siteaccess'|i18n('newsletter/datatype/ownewslettermailinglist')}</label>
		{$newsletter_list_content.siteaccess_list|implode( ', ')}
	</div>
</div>
<div class="block float-break">
	{* Auto approve registered users. *}
	<div class="element">
		<label>{'Automatically approve subscription after user registration?'|i18n('newsletter/datatype/ownewslettermailinglist')}:</label>
		{$newsletter_list_content.auto_approve_registered_user|choose( 'No'|i18n( 'newsletter/datatype/ownewslettermailinglist' ), 'Yes'|i18n( 'newsletter/datatype/ownewslettermailinglist' ) )}
	</div>
</div>

<div class="break"></div>

{undef $newsletter_list_content}
