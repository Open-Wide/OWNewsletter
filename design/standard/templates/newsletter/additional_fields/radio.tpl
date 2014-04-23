<div class="block {if $attribute_warning_array|contains(concat('additional_data_',$field_identifier))}nl-error{/if}">
	<label for="Subscription_additional_data_{$field_identifier}">
		{$field_configuration.label|i18n( 'newsletter/additional_fields' )} {if $field_configuration.required}*{/if}:
	</label>

    {foreach $field_configuration.select_options as $value => $name}
        <input class="halfbox" id="Subscription_additional_data_{$field_identifier}"
           type="radio"
           name="NewsletterUser[additional_data][{$field_identifier}]"
           value="{$value|wash}"
           {if $newsletter_user.additional_data.$field_identifier|eq($value)}checked="checked"{/if}
            {if $field_configuration.help_message}title="{$field_configuration.help_message|i18n( 'newsletter/additional_fields' )}"{/if}
            /> {$name|wash}
    {/foreach}

</div>