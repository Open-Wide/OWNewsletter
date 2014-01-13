<div class="block {if $attribute_warning_array|contains(concat('additional_data_',$field_identifier))}nl-error{/if}">
	<label for="Subscription_additional_data_{$field_identifier}">
		{$field_configuration.label|i18n( 'newsletter/additional_fields' )} {if $field_configuration.required}*{/if}:
	</label>
	<select id="Subscription_additional_data_{$field_identifier}" 
			name="NewsletterUser[additional_data][{$field_identifier}][]" 
			multiple="multiple" 
			{if $field_configuration.help_message}title="{$field_configuration.help_message|i18n( 'newsletter/additional_fields' )}"{/if}>
		{foreach $field_configuration.select_options as $value => $name}
			<option value="{$value|wash}" 
					{if $newsletter_user.additional_data.$field_identifier|contains($value)}selected="selected"{/if}>
				{$name|wash}
			</option>
		{/foreach}
	</select>
</div>