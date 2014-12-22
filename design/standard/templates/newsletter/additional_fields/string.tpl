<div class="block {if $attribute_warning_array|contains(concat('additional_data_',$field_identifier))}nl-error{/if}">
    <label for="Subscription_additional_data_{$field_identifier}">
        {$field_configuration.label|i18n( 'newsletter/additional_fields' )} {if $field_configuration.required}*{/if}:
    </label>
    <input class="halfbox" 
           id="Subscription_additional_data_{$field_identifier}" 
           type="text" 
           name="NewsletterUser[additional_data][{$field_identifier}]" 
           value="{$newsletter_user.additional_data.$field_identifier}" 
           {if $field_configuration.help_message}title="{$field_configuration.help_message|i18n( 'newsletter/additional_fields' )}"{/if}
           />
</div>