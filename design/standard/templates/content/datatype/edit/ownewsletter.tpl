{*?template charset=utf-8?*}
{if is_unset( $attribute_base )}
  {def $attribute_base='ContentObjectAttribute'}
{/if}

{def $datatype_name='ownewsletter'
     $newsletter_object = $attribute.content}

{def $available_siteaccess_list = $newsletter_object.available_siteaccess_list
     $available_skin_list =  $newsletter_object.available_skin_list
     $default_mailing_lists_ids = $newsletter_object.default_mailing_lists_ids
     $main_siteaccess = $newsletter_object.main_siteaccess
     $sender_name = $newsletter_object.sender_name
     $sender_mail = $newsletter_object.sender_mail
     $email_receiver_test = $newsletter_object.email_receiver_test_list|implode(';')
     $skin_name = $newsletter_object.skin_name
     $personalize_content = $newsletter_object.personalize_content
	 $available_mailing_lists = $newsletter_object.available_mailing_lists
     }
{* default value main_siteaccess *}
{if $main_siteaccess|eq('') }
    {set $main_siteaccess = $available_siteaccess_list[0]}
{/if}

{if $sender_mail|eq('') }
    {set $sender_mail = ezini('MailSettings','AdminEmail')}
{/if}

{if $email_receiver_test|eq('') }
    {set $email_receiver_test = ezini('MailSettings','AdminEmail')}
{/if}

<hr>


<table class="list" cellspacing="0">
<tr>
    <th>{'Default mailing list selection'|i18n('newsletter/datatype/ownewsletter')}</th>
    <th>{'Mailing list'|i18n('newsletter/datatype/ownewsletter')}</th>
</tr>
{foreach $available_mailing_lists as $available_mailing_list sequence array('bglight','bgdark') as $style}
<tr class="{$style}">
    <td><input type="checkbox" name="{$attribute_base}_{$datatype_name}_DefaultMailingListSelection_{$attribute.id}[]" value="{$available_mailing_list.contentobject_id}" {if $default_mailing_lists_ids|contains( $available_mailing_list.contentobject_id )}checked{/if}></td>
    <td>{$available_mailing_list.name|wash( )}</td>
</tr>
{/foreach}
</table>
<table class="list" cellspacing="0">
<tr>
    <th>{'Main siteaccess'|i18n('newsletter/datatype/ownewsletter')} *</th>
    <th>{'Siteaccess'|i18n('newsletter/datatype/ownewsletter')}</th>
</tr>
{foreach $available_siteaccess_list as $sitaccess_name => $siteaccess_info sequence array('bglight','bgdark') as $style}
<tr class="{$style}">
    <td><input type="radio" name="{$attribute_base}_{$datatype_name}_MainSiteaccess_{$attribute.id}" value="{$sitaccess_name}" {if $main_siteaccess|eq( $sitaccess_name )}checked{/if}></td>
    <td>{$sitaccess_name|wash( )} ( {$siteaccess_info.locale|wash} - {$siteaccess_info.site_url|wash} )</td>
</tr>
{/foreach}
</table>

<hr>
{* sender_mail *}
<label>{'Newsletter sender email'|i18n('newsletter/datatype/ownewsletter')} *</label>
<input type="text" class="halfbox" name="{$attribute_base}_{$datatype_name}_SenderMail_{$attribute.id}" value="{$sender_mail}" />

{* sender_name *}
<label>{'Newsletter sender name'|i18n('newsletter/datatype/ownewsletter')}</label>
<input type="text" class="halfbox" name="{$attribute_base}_{$datatype_name}_SenderName_{$attribute.id}" value="{$sender_name}" />

<hr>
{* email_receiver_test *}
<label>{'Newsletter default test receiver emails (separated by ;)'|i18n('newsletter/datatype/ownewsletter')} *</label>
<input type="text" class="halfbox" name="{$attribute_base}_{$datatype_name}_EmailReceiverTest_{$attribute.id}" value="{$email_receiver_test}" />

<hr>

{* skin_name *}
<label>{'Newsletter skin name'|i18n('newsletter/datatype/ownewsletter')}</label>
{foreach $available_skin_list as $skin_name_2}
<input type="radio" name="{$attribute_base}_{$datatype_name}_SkinName_{$attribute.id}" value="{$skin_name_2}" {if or( eq( $skin_name, $skin_name_2), eq( $available_skin_list|count(), 1) ) }checked="checked"{/if} />{$skin_name_2|wash}
{/foreach}

<hr>
<label>{'Personalize newsletter if data are available?'|i18n('newsletter/datatype/ownewsletter')} {*# {$personalize_content} #*}</label>
<input type="radio" name="{$attribute_base}_{$datatype_name}_PersonalizeContent_{$attribute.id}" value="0"{$personalize_content|choose(' checked', '')} /> {'No'|i18n('newsletter/datatype/ownewsletter')}
<input type="radio" name="{$attribute_base}_{$datatype_name}_PersonalizeContent_{$attribute.id}" value="1"{$personalize_content|choose('', ' checked')} /> {'Yes'|i18n('newsletter/datatype/ownewsletter')}
{undef}

