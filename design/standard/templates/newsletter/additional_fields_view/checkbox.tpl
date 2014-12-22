<div class="block">
    <h4>
        {$field_configuration.label|i18n( 'newsletter/additional_fields' )}
    </h4>
    <span>
        {if $newsletter_user.additional_data.$field_identifier}
            {'Yes'|i18n( 'newsletter/additional_fields' )}
        {else}
            {'No'|i18n( 'newsletter/additional_fields' )}
        {/if}
    </span>
</div>