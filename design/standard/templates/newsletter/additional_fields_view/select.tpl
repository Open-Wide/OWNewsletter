<div class="block">
	<h4>
		{$field_configuration.label|i18n( 'newsletter/additional_fields' )}
	</h4>
	<span>{$newsletter_user.additional_fields.$field_identifier.select_options[$newsletter_user.additional_data.$field_identifier]|wash()}</span>
</div>