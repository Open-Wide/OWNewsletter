{def $page_uri = concat( 'newsletter/user/', $newsletter_user.id)}
<div class="newsletter newsletter-user_view">
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{$newsletter_user.name|wash()} &lt;{$newsletter_user.email|wash()}&gt; [{'Newsletter user'|i18n( 'newsletter/user')|wash()}]</h1>
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
                                <tr>
                                    <th>{'Name'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.name|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'Id'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.id|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'E-mail'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.email|wash()}</td>
                                </tr>

                                <tr>
                                    <th>{'Salutation'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.salutation_name|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'First name'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.first_name|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'Last name'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.last_name|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'Additional data'|i18n( 'newsletter/user' )}</th>
                                    <td>
                                        {def $additional_fields = fetch('newsletter', 'user_additional_fields')}
                                        {foreach $additional_fields as $field_identifier => $field_configuration}
                                            {include uri=concat('design:newsletter/additional_fields_view/',$field_configuration.type,'.tpl') field_identifier=$field_identifier field_configuration=$field_configuration newsletter_user=$newsletter_user}
                                        {/foreach}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{'Status'|i18n( 'newsletter/user' )}</th>
                                    <td>
                                        <img src={'1x1.gif'|ezimage} alt="{$newsletter_user.status_name}" title="{$newsletter_user.status_name}" class="icon12 icon_s_{$newsletter_user.status_identifier}" />
                                        {$newsletter_user.status_name|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'eZ user id'|i18n( 'newsletter/user' )}</th>
                                    <td>
                                        {if is_object($newsletter_user.ez_user)}
                                            <a href={concat('content/view/full/', $newsletter_user.ez_user.contentobject.main_node_id )|ezurl}>{$newsletter_user.ez_user.contentobject.name|wash()}</a> ({if $newsletter_user.ez_user.is_enabled}{'enabled'|i18n( 'newsletter/user' )}{else}{'disabled'|i18n( 'newsletter/user' )}{/if})
                                        {elseif $newsletter_user.ez_user_id|ne( 0 )}
                                            <b> {'Ez user with id %ez_user_id does not exist anymore!'|i18n( 'newsletter/user', '', hash('%ez_user_id', $newsletter_user.ez_user_id ) )}</b>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{'Creator'|i18n( 'newsletter/user' )}</th>
                                    <td>
                                        {if is_object($newsletter_user.creator)}
                                            {$newsletter_user.creator.name|wash()}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th> {'Created'|i18n( 'newsletter/user' )} </th>
                                    <td> {$newsletter_user.created|l10n( shortdatetime )} </td>
                                </tr>
                                <tr>
                                    <th> {'Modifier'|i18n( 'newsletter/user' )} </th>
                                    <td>
                                        {if is_object($newsletter_user.modifier)}
                                            {$newsletter_user.modifier.name|wash()}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th> {'Modified'|i18n( 'newsletter/user' )} </th>
                                    <td>
                                        {if $newsletter_user.modified|ne(0)}
                                            {$newsletter_user.modified|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th> {'Confirmed'|i18n( 'newsletter/user' )} </th>
                                    <td>
                                        {if $newsletter_user.confirmed|ne(0)}
                                            {$newsletter_user.confirmed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{'Removed'|i18n( 'newsletter/user' )}</th>
                                    <td>
                                        {if $newsletter_user.removed|ne(0)}
                                            {$newsletter_user.removed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{'Bounced'|i18n( 'newsletter/user' )}</th>
                                    <td>
                                        {if $newsletter_user.bounced|ne(0)}
                                            {$newsletter_user.bounced|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{'Blacklisted'|i18n( 'newsletter/user' )}</th>
                                    <td>
                                        {if $newsletter_user.blacklisted|ne(0)}
                                            {$newsletter_user.blacklisted|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{'Bounce count'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.bounce_count|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'Note'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.note|nl2br}</td>
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
                                            <form id="submit_newsletter_user_{$newsletter_user.id}" method="post" style="display:inline;" action={concat( 'newsletter/user/', $newsletter_user.id)|ezurl()}>
                                                <input type="hidden" name="RedirectUrlActionCancel" value={$page_uri} />
                                                <input type="hidden" name="RedirectUrlActionSuccess" value={$page_uri} />
                                                <input type="hidden" name="RedirectUrlActionRemove" value={'newsletter/user'} />
                                                <input {if $newsletter_user.status_identifier|eq('blacklisted')}class="button-disabled"{else}class="button"{/if} 
                                                                                                                type="submit" name="SubmitNewsletterUserButton" value="{'Edit'|i18n( 'newsletter/user' )}" />
                                                <input {if $newsletter_user.is_confirmed}class="button-disabled"{else}class="button"{/if} 
                                                                                         type="submit" name="ConfirmNewsletterUserButton" value="{'Confirm'|i18n( 'newsletter/user' )}" />
                                                <input {if $newsletter_user.is_removed}class="button-disabled"{else}class="button"{/if} 
                                                                                       type="submit" name="RemoveNewsletterUserButton" value="{'Remove'|i18n( 'newsletter/user' )}" />
                                                <input {if $newsletter_user.is_removed|not()}class="button-disabled"{else}class="button"{/if} 
                                                                                             type="submit" name="RemoveForGoodNewsletterUserButton" value="{'Remove for good'|i18n( 'newsletter/user' )}" onclick="return confirm('{'Do you really want to delete this user?'|i18n( 'newsletter/user' )|wash()}');" />
                                            </form>

                                            <form id="submit_blacklist_{$newsletter_user.id}}" method="post" style="display:inline;" action={'newsletter/blacklist'|ezurl()}>
                                                <input type="hidden" name="RedirectUrlActionSuccess" value={$page_uri} />
                                                <input type="hidden" name="Email" value="{$newsletter_user.email|wash()}" />
                                                {if $newsletter_user.status_identifier|ne('blacklisted')}
                                                    <input class="button" type="submit" name="AddBlacklistItemButton" value="{'Add to blacklist'|i18n( 'newsletter/user' )}" />
                                                {else}
                                                    <input class="button" type="submit" name="RemoveBlacklistItemButton" value="{'Remove from blacklist'|i18n( 'newsletter/user' )}" />
                                                {/if}
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{* =============  list of all subscriptions ============ *}
        {def $subscription_list = $newsletter_user.subscription_list
             $subscription_list_count = $subscription_list|count}
        <div class="context-block">
            <div class="box-header">
                <div class="box-tc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tl">
                                <div class="box-tr">
                                    <h2 class="context-title">{'Subscriptions'|i18n( 'newsletter/user' )} [{$subscription_list_count}]</h2>
                                    <div class="header-subline">
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

                            {if $subscription_list_count|gt(0)}
                                <div class="overflow-table">
                                    <table class="list" cellspacing="0">
                                        <tr>
                                            <th class="tight">
                                                {'Mailing list'|i18n( 'newsletter/user' )}
                                            </th>
                                            <th>
                                                {'Status'|i18n( 'newsletter/user' )}
                                            </th>
                                            <th>
                                                {'Created'|i18n( 'newsletter/user' )}
                                            </th>
                                            <th>
                                                {'Modified'|i18n( 'newsletter/user' )}
                                            </th>
                                            <th>
                                                {'Approved'|i18n( 'newsletter/user' )}
                                            </th>
                                            <th>
                                                {'Deactivated'|i18n( 'newsletter/user' )}
                                            </th>
                                            <th>
                                                {* actions *}
                                            </th>
                                        </tr>
                                        {foreach $subscription_list as $subscription sequence array( bglight, bgdark ) as $style}
                                            <tr class="{$style}">
                                                <td>
                                                    <a href={$subscription.mailing_list_contentobject.main_node.url_alias|ezurl}>{$subscription.mailing_list_contentobject.name|wash()} </a>
                                                </td>
                                                <td>
                                                    <img src={'1x1.gif'|ezimage} alt="{$subscription.status_name}" title="{$subscription.status_name}" class="icon12 icon_s_{$subscription.status_identifier}" />
                                                </td>
                                                <td>
                                                    {cond( $subscription.created|gt(0), $subscription.created|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
                                                </td>
                                                <td>
                                                    {cond( $subscription.modified|gt(0), $subscription.modified|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
                                                </td>
                                                <td>
                                                    {cond( $subscription.approved|gt(0), $subscription.approved|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
                                                </td>
                                                <td>
                                                    {cond( $subscription.inactived|gt(0), $subscription.inactived|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
                                                </td>
                                                <td class="tight" style="white-space: nowrap;">
                                                    <form class="inline" action={concat('newsletter/user/', $subscription.id )|ezurl()} method="post">
                                                        <input class="button" type="submit" value="{'Details'|i18n( 'newsletter/user' )}" title="{'Subscription details'|i18n( 'newsletter/user' )}" name="ViewSubscriptionDetail" />
                                                    </form>
                                                    <form class="inline" action={concat( '/newsletter/user/', $subscription.id )|ezurl()} method="post">
                                                        <input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
                                                        <input  {if $subscription.can_be_approved|not()}class="button-disabled" disabled="disabled"{else}class="button"{/if} type="submit" value="{'Approve'|i18n( 'newsletter/user' )}" name="ApproveSubscriptionButton" title="{'Approve subscription'|i18n( 'newsletter/user' )}" />
                                                    </form>
                                                    <form class="inline" action={concat( '/newsletter/user/', $subscription.id )|ezurl()} method="post">
                                                        <input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
                                                        <input  {if $subscription.can_be_removed|not()}class="button-disabled" disabled="disabled"{else}class="button"{/if} type="submit" value="{'Remove'|i18n( 'newsletter/user' )}" name="RemoveSubscriptionButton" title="{'Remove subscription'|i18n( 'newsletter/user' )}" />
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
                            </div>
                        {else}

                            {'No subscription'|i18n( 'newsletter/user' )}
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