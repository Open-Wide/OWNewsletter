{set-block scope=global variable=cache_ttl}0{/set-block}
{def $base_uri = $node.url_alias
	 $page_uri = $base_uri}
{if $view_parameters.status|ne( '' )}
    {set $page_uri = concat( $page_uri, '/(status)/', $view_parameters.status )}
{/if}
{if $view_parameters.offset|gt( 0 )}
    {set $page_uri = concat( $page_uri, '/(offset)/', $view_parameters.offset )}
{/if}

<div class="context-block">
    <div class="box-header"></div>
    <div class="box-content">
        <div class="block">
            <div class="left">
                <form name="CreateNewNewsletterUser" method="post" style="display:inline;" action={'newsletter/user'|ezurl}>
                    <input type="hidden" name="RedirectUrlActionCancel" value="{$page_uri}" />
                    <input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
                    <input type="text" name="Email" />
                    <input type="hidden" name="MailingListContentObjectID" value='{$node.contentobject_id}'/>
                    <input class="button" type="submit" name="SubscribeNewsletterUserButton" value="{'Subscribe this user to the mailing list'|i18n( 'design/admin/node/view/full' )}" />
                </form></div>
            <div class="right">
                <form name="ImportSubscription" method="post" style="display:inline;" action={concat('newsletter/subscription_import/',$node.contentobject_id)|ezurl}>
                    <input class="button" type="submit" name="GoOnImportSubscriptionButton" value="{'Import subscriptions'|i18n( 'design/admin/node/view/full' )}">
                </form>
                <form name="ExportSubscription" method="post" style="display:inline;" action={concat('newsletter/subscription_export/',$node.contentobject_id)|ezurl}>
                    <input class="button" type="submit" name="GoOnExportSubscriptionButton" value="{'Export subscriptions'|i18n( 'design/admin/node/view/full' )}">
                </form>
            </div>
            <div class="break"></div>
        </div>
    </div>
</div>
<div id="content-sub-items-list" class="content-navigation-childlist">
    <div class="newsletter newsletter-user_list">
        {def $limit = 50}
        {if ezpreference( 'admin_user_list_limit' )}
            {switch match=ezpreference( 'admin_user_list_limit' )}
            {case match=1}
            {set $limit=10}
            {/case}
            {case match=2}
            {set $limit=25}
            {/case}
            {case match=3}
            {set $limit=50}
            {/case}
            {/switch}
        {/if}
        <div class="context-block">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-content">

                        <div class="context-toolbar">
                        </div>
                        <div class="break float-break">
                        </div>
                        {if $children_count|gt(0)}
                            {def $subscription_list = fetch( 'newsletter', 'subscription_list', hash(
									'mailing_list_contentobject_id', $node.contentobject_id,
									'filter_status', $view_parameters.status,
									'limit', $limit,
									'offset', $view_parameters.offset,
                                    'user_status', 'confirmed'
								) )}
                            <div class="content-navigation-childlist overflow-table">

                                <table class="list" cellspacing="0">
                                    <tr>
                                        <th class="tight">{'Id'|i18n('newsletter/user')}</th>
                                        <th>{'E-mail'|i18n( 'newsletter/user' )}</th>
                                        <th>{'Name'|i18n( 'newsletter/user' )}</th>
                                        <th>{'eZ Publish User'|i18n('newsletter/user')}</th>
                                        <th>{'Status'|i18n( 'newsletter/user' )}</th>
                                        <th>{'Modified'|i18n( 'newsletter/user' )}</th>
                                        <th class="tight">&nbsp;</th>
                                    </tr>
                                    {foreach $subscription_list as $subscription sequence array( bglight, bgdark ) as $style}
                                        <tr class="{$style}">
                                            <td>{$subscription.id|wash}</td>
                                            <td>
                                                <a href={concat('newsletter/user/',$subscription.newsletter_user.id)|ezurl}>{$subscription.newsletter_user.email|wash}</a>
                                            </td>
                                            <td>
                                                {$subscription.newsletter_user.name}
                                            </td>
                                            <td>
                                                {if $subscription.newsletter_user.ez_user_id|gt( '0' )}
                                                    {def $user_object = fetch( 'content', 'object', hash( 'object_id', $subscription.newsletter_user.ez_user_id ) )}
                                                    {if $user_object}
                                                        <a href="{$user_object.main_node.url_alias|ezurl( 'no' )}">{$user_object.name|wash}</a>
                                                    {/if}
                                                    {undef $user_object}
                                                {/if}
                                            </td>
                                            <td> 
                                                <img src={'16x16.gif'|ezimage} 
                                                     alt="{cond( eq($subscription.newsletter_user.status_identifier,'confirmed') ,  cond( eq($subscription.status_identifier,'pending'), $subscription.newsletter_user.status_name|wash,  $subscription.status_name|wash)  , $subscription.newsletter_user.status_name|wash )}" 
                                                     class="icon12 icon_s_{cond( eq($subscription.newsletter_user.status_identifier,'confirmed') ,  cond( eq($subscription.status_identifier,'pending'), $subscription.newsletter_user.status_identifier,  $subscription.status_identifier)  , $subscription.newsletter_user.status_identifier )}" 
                                                     title="{cond( eq($subscription.newsletter_user.status_identifier,'confirmed') ,  cond( eq($subscription.status_identifier,'pending'), $subscription.newsletter_user.status_name|wash,  $subscription.status_name|wash)  , $subscription.newsletter_user.status_name|wash )} ({cond( eq($subscription.newsletter_user.status_identifier,'confirmed') ,  cond( eq($subscription.status_identifier,'pending'), $subscription.newsletter_user.status,  $subscription.status)  , $subscription.newsletter_user.status )})" />
                                            </td>
                                            <td>
                                                {cond( $subscription.modified|gt(0), $subscription.modified|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
                                            </td>
                                            <td class="tight" style="white-space: nowrap;">
                                                <form class="inline" action={concat('newsletter/user/', $subscription.id )|ezurl()} method="post">
                                                    <input class="button" type="submit" value="{'Details'|i18n( 'newsletter/user' )}" title="{'Subscription details'|i18n( 'newsletter/user' )}" name="ViewSubscriptionDetail" />
                                                </form>
                                                <form class="inline" action={concat( '/newsletter/user/', $subscription.id )|ezurl()} method="post">
                                                    <input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
                                                    <input  {if $subscription.can_be_approved|not()}class="button-disabled" disabled="disabled"{else}class="button"{/if} type="submit" value="{'Approve'|i18n( 'newsletter/user' )}" name="ApproveSubscriptionButton" title="{'Approve subscription'|i18n( 'newsletter/user' )}" />
                                                </form>
                                                <form class="inline" action={concat( 'newsletter/user/', $subscription.newsletter_user.id )|ezurl()} method="post">
                                                    <input type="hidden" name="RedirectUrlActionCancel" value="{$page_uri}" />
                                                    <input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
                                                    <input class="button" type="submit" value="{'Edit'|i18n( 'newsletter/user' )}" title="{'Edit newsletter user'|i18n( 'newsletter/user' )}" name="SubmitNewsletterUserButton" />
                                                </form>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            </div>

                            {* Navigator. *}
                            <div class="context-toolbar subitems-context-toolbar">
                                {include name='Navigator'
                             uri='design:navigator/google.tpl'
                             page_uri=$page_uri
                             item_count=$subscription_list_count
                             view_parameters=$view_parameters
                             item_limit=$limit}
                            </div>
                        {else}
                            <p>{'No subscribtion'|i18n(  'design/admin/node/view/full'  )}</p>
                        {/if}
                    </div>
                </div>
                <div class="controlbar">
                    <div class="box-bc">
                        <div class="box-ml">
                            <div class="box-mr">
                                <div class="box-tc">
                                    <div class="box-bl">
                                        <div class="box-br">
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
</div>
<div id="bpg"></div>
<div id="to-dialog-container"></div>