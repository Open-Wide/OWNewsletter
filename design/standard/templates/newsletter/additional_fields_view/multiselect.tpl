<div class="block">
	<h4>
		{$field_configuration.label|i18n( 'newsletter/additional_fields' )}
	</h4>
	<ul>
		{foreach $newsletter_user.additional_data.$field_identifier as $value}
			<li>{$newsletter_user.additional_fields.$field_identifier.select_options[$value]|wash()}</li>
		{/foreach}
	</ul>
</div>