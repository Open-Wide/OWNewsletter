{if $attribute_warning_array|is_set()|not()}
    {def $attribute_warning_array=array()}
{/if}
<div class="newsletter newsletter-user_edit">
    {if and( is_set( $message_feedback ), $message_feedback|ne( '' ) )}
        <div class="block">
            <div class="message-feedback">
                <h2>{$message_feedback|wash}</h2>
            </div>
        </div>
    {/if}
    {if and( is_set( $warning_array ), $warning_array|count|ne( 0 ) )}
        <div class="block">
            <div class="message-warning">
                <h2>{'Input did not validate'|i18n('newsletter/warning_message')}</h2>
                <ul>
                    {foreach $warning_array as $message}
                        <li><span class="text">{$message|i18n('newsletter/warning_message')}</span></li>
                        {/foreach}
                </ul>
            </div>
        </div>
    {/if}

    <form action={concat( 'newsletter/user/', $newsletter_user.id )|ezurl()} method="post">
        <input type="hidden" name="RedirectUrlActionCancel" value="{$redirect_url_action_cancel}" />
        <input type="hidden" name="RedirectUrlActionSuccess" value="{$redirect_url_action_success}" />
        <div class="context-block">
            <div class="box-header">
                <div class="box-tc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tl">
                                <div class="box-tr">
                                    <h1 class="context-title">
                                        {if $newsletter_user.id}
                                            {$newsletter_user.name|wash} &lt;{$newsletter_user.email|wash}&gt;
                                        {else}
                                            {'New'|i18n( 'newsletter/user' )}
                                        {/if} [{'Newsletter user'|i18n( 'newsletter/user' )|wash}]</h1>
                                    <div class="header-mainline">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-content">
                        <div class="context-attributes">
                            <div class="block float-break">
                                <table class="list">
                                    {if $newsletter_user.name}
                                        <tr>
                                            <th>
                                                {'Name'|i18n( 'newsletter/user' )}
                                            </th>
                                            <td>
                                                {$newsletter_user.name|wash}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if $newsletter_user.id}
                                        <tr>
                                            <th>
                                                {'Id'|i18n( 'newsletter/user' )}
                                            </th>
                                            <td>
                                                {$newsletter_user.id|wash}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if $newsletter_user.status}
                                        <tr>
                                            <th>
                                                {'Status'|i18n( 'newsletter/user' )}
                                            </th>
                                            <td title="{$newsletter_user.status|wash}">
                                                {$newsletter_user.status_name|wash}
                                            </td>
                                        </tr>
                                    {/if}
                                    <tr {if $attribute_warning_array|contains('email')}class="nl-error"{/if}>
                                        <th>
                                            {'E-mail'|i18n( 'newsletter/user' )} *
                                        </th>
                                        <td>
                                            <input  class="halfbox" type="text" name="NewsletterUser[email]" value="{$newsletter_user.email|wash}">
                                        </td>
                                    </tr>
                                    <tr {if $attribute_warning_array|contains('salutation')}class="nl-error"{/if}>
                                        <th>
                                            {'Salutation'|i18n( 'newsletter/user' )} {if $required_fields|contains('salutation')}*{/if}
                                        </th>
                                        <td title="{'Salutation'|i18n( 'newsletter/user' )}">
                                            {foreach $available_salutation_array as $salutation_id => $salutataion_name}
                                                <input type="radio" name="NewsletterUser[salutation]" value="{$salutation_id|wash}"{if $newsletter_user.salutation|eq( $salutation_id )} checked="checked"{/if} title="{$salutataion_name|wash}" />{$salutataion_name|wash}&nbsp;
                                            {/foreach}
                                        </td>
                                    </tr>
                                    <tr {if $attribute_warning_array|contains('first_name')}class="nl-error"{/if}>
                                        <th>
                                            {'First name'|i18n( 'newsletter/user' )} {if $required_fields|contains('first_name')}*{/if}
                                        </th>
                                        <td>
                                            <input class="halfbox" type="text" name="NewsletterUser[first_name]" value="{$newsletter_user.first_name|wash}" title="{'First name of newsletter user.'|i18n( 'newsletter/user' )}"
                                        </td>
                                    </tr>
                                    <tr {if $attribute_warning_array|contains('last_name')}class="nl-error"{/if}>
                                        <th>
                                            {'Last name'|i18n( 'newsletter/user' )} {if $required_fields|contains('last_name')}*{/if}
                                        </th>
                                        <td>
                                            <input class="halfbox" type="text" name="NewsletterUser[last_name]" value="{$newsletter_user.last_name|wash}" title="{'Last name of newsletter user.'|i18n( 'newsletter/user' )}"
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{'Additional data'|i18n( 'newsletter/user' )}</th>
                                        <td>
                                            {def $additional_fields = fetch('newsletter', 'user_additional_fields')}
                                            {foreach $additional_fields as $field_identifier => $field_configuration}
                                                {include uri=concat('design:newsletter/additional_fields/',$field_configuration.type,'.tpl') field_identifier=$field_identifier field_configuration=$field_configuration newsletter_user=$newsletter_user attribute_warning_array=$attribute_warning_array}
                                            {/foreach}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {'Note'|i18n( 'newsletter/user' )}
                                        </th>
                                        <td>
                                            <textarea class="box" name="NewsletterUser[note]" cols="50" rows="10">{$newsletter_user.note|wash}</textarea>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            {'Subscriptions'|i18n( 'newsletter/user' )}
                                        </th>
                                        <td
                                            {def $newsletter_root_node_id = ezini( 'NewsletterSettings', 'RootFolderNodeId', 'newsletter.ini' )
                                            $newsletter_system_node_list = fetch( 'content', 'list', hash(
                                                            'parent_node_id', $newsletter_root_node_id,
                                                            'class_filter_type', 'include',
                                                            'class_filter_array', array( 'newsletter_system' ),
                                                            'sort_by', array( 'name', true() ),
                                                            'limitation', hash( ) 
                                                        ) )
                                                $available_subscription_status_list = fetch( 'newsletter', 'available_subscription_status' )
                                                $status_id_array_enabled =  array()}
                                            {* set available status id for status selection list *}
                                            {if $newsletter_user.status|ne( 0 )}
                                                {set $status_id_array_enabled = array( 2, 5 )}
                                            {/if}

                                            {foreach $newsletter_system_node_list as $system_node}
                                                <div class="newsletter-system-design">
                                                    <h2>{$system_node.name|wash}</h2>
                                                    {def $mailing_list_collection_node_list = fetch( 'content', 'list', hash(
                                                            'parent_node_id', $system_node.node_id,
                                                            'class_filter_type', 'include',
                                                            'class_filter_array', array( 'newsletter_mailing_list_collection' ),
                                                            'limitation', hash( ),
                                                            'sort_by', $system_node.sort_array
                                                        ) )}
                                                    {foreach $mailing_list_collection_node_list as $mailing_list_collection_node}
                                                        {def $mailing_list_node_list = fetch( 'content', 'list', hash(
                                                                'parent_node_id', $mailing_list_collection_node.node_id,
                                                                'class_filter_type', 'include',
                                                                'class_filter_array', array( 'newsletter_mailing_list' ),
                                                                'limitation', hash( ),
                                                                'sort_by', $mailing_list_collection_node.sort_array
                                                            ) )}
                                                        <h3>{$mailing_list_collection_node.name|wash}</h3>
                                                        <table border="0" width="100%" class="list {if $attribute_warning_array|contains('subscription')}nl-error{/if}">
                                                            {foreach $mailing_list_node_list as $index => $mailing_list_node sequence array( bglight, bgdark ) as $style}
                                                                <tr class="{$style}">
                                                                    {def $mailing_list_id = $mailing_list_node.contentobject_id
                                                                        $list_content = $mailing_list_node.data_map.newsletter_list.content
                                                                        $created = 0
                                                                        $confirmed = 0
                                                                        $approved = 0
                                                                        $removed = 0
                                                                        $subscription = null
                                                                        $status = -1
                                                                        $is_removed = false()
                                                                        $td_counter = 0
                                                                        $modified = 0}
                                                                    {if is_set( $subscription_list[ $mailing_list_id ] )}
                                                                        {set $subscription = $subscription_list[ $mailing_list_id ]
                                                                            $created = $subscription.created
                                                                            $confirmed = $subscription.confirmed
                                                                            $removed = $subscription.removed
                                                                            $approved = $subscription.approved
                                                                            $bounced = $subscription.bounced
                                                                            $blacklisted = $subscription.blacklisted
                                                                            $status = $subscription.status
                                                                            $is_inactived = $subscription.is_inactived
                                                                            $modified = $subscription.modified
                                                                            $status_id_array_enabled = $status_id_array_enabled|append( $status )|unique}
                                                                    {/if}
                                                                    <td width="300">
                                                                        <input type="hidden" name="NewsletterUser[subscription_list][{$mailing_list_id}][mailing_list_contentobject_id]" value="{$mailing_list_id}" title="" />
                                                                        {$mailing_list_node.name|wash}
                                                                    </td>
                                                                    <td>
                                                                        <select name="NewsletterUser[subscription_list][{$mailing_list_id}][status]">
                                                                            {if $status|eq(-1)}<option value="-1">-</option>{/if}
                                                                            {def $status_already_selected = false()}
                                                                            {foreach $available_subscription_status_list as $status_id => $status_name}
                                                                                {def $status_timestamp = 0
                                                                                    $extra_string = false()
                                                                                    $status_is_disabled = false()
                                                                                    $status_is_selected = false()}

                                                                                {if $status_id_array_enabled|contains($status_id)|not}
                                                                                    {set $status_is_disabled = true()}
                                                                                {/if}
                                                                                {if and( $status_id|eq( 2 ), $mailing_list_id|eq( $add_subscription_for_list_id ), $status_already_selected|not )}
                                                                                    {set $status_is_selected = true()}
                                                                                    {set $status_already_selected = true()}
                                                                                {elseif and( $status_id|eq( $status ), $status_already_selected|not )}
                                                                                    {set $status_is_selected = true()}
                                                                                    {set $status_already_selected = true()}
                                                                                {/if}

                                                                                {*pending*}
                                                                                {if $status_id|eq( 0 )}
                                                                                    {set $status_timestamp = $created}
                                                                                    {*approved*}
                                                                                {elseif $status_id|eq( 2 )}
                                                                                    {set $status_timestamp = $approved}
                                                                                    {*inactived*}
                                                                                {elseif $status_id|eq( 5 )}
                                                                                    {set $status_timestamp = $inactived}
                                                                                {/if}
                                                                                <option value="{$status_id}"{if $status_is_selected} selected="selected"{/if}{if $status_is_disabled}disabled="disabled"{/if}>{if $status|eq( $status_id )}[{/if}{$status_name}{if $status|eq( $status_id )}]{/if}{$extra_string}{if $status_timestamp|ne(0)} - {$status_timestamp|datetime( 'custom', '%j.%m.%Y %H:%i' )}{/if}</option>
                                                                                {undef $status_timestamp $extra_string $status_is_disabled $status_is_selected}
                                                                            {/foreach}
                                                                            {undef $status_already_selected}
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        {if $modified|ne(0)}{'Modified'|i18n( 'newsletter/user' )}: {$modified|datetime( 'custom', '%j.%m.%Y %H:%i' )}{/if}
                                                                    </td>
                                                                    {undef $mailing_list_id $list_content $subscription_list $subscription $created $confirmed $subscription $removed $approved $bounced $blacklisted $status $is_inactived $td_counter $modified}
                                                                </tr>
                                                            {/foreach}
                                                        </table>
                                                    {/foreach}
                                                </div>
                                                {undef $mailing_list_node_list}
                                            {/foreach}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="controlbar">
                    <div class="box-bc">
                        <div class="box-ml">
                            <div class="box-mr">
                                <div class="box-tc">
                                    <div class="box-bl">
                                        <div class="box-br">
                                            <div class="left">
                                                {if $newsletter_user.status|eq(8)}
                                                    <input class="button-disabled" type="button" value="{'Store and exit'|i18n( 'newsletter/user' )}" />
                                                {else}
                                                    <input class="button" type="submit" name="SubmitNewsletterUserButton" value="{'Store and exit'|i18n( 'newsletter/user' )}" />
                                                {/if}
                                                <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'newsletter/user' )}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>