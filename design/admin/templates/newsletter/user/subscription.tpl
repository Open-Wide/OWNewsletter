{def	$page_uri = concat( 'newsletter/user/', $subscription.newsletter_user_id, '/', $subscription.mailing_list_contentobject_id )}
<div class="newsletter newsletter-subscription_view">
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Subscription'|i18n('newsletter/user')}</h1>
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

                            {if $message|ne('')}
								<div class="message">
									<h2>{$message|wash()}</h2>
								</div>
                            {/if}

                            <table class="list">
                                <tr>
                                    <th>
                                        {'Id'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {$subscription.id|wash()}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Mailing list'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        <a href={$subscription.mailing_list_contentobject.main_node.url_alias|ezurl()}>{$subscription.mailing_list_contentobject.name|wash()}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Newsletter user'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        <a href={concat('newsletter/user/',$subscription.newsletter_user_id )|ezurl()}>{$subscription.newsletter_user.name|wash()} &lt;{$subscription.newsletter_user.email|wash()}&gt;</a> ({$subscription.newsletter_user.status_name})
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Status'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {$subscription.status_name|wash()}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Created'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {$subscription.created|l10n( shortdatetime )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Creator'|i18n('newsletter/user')}
                                    </th>
                                    <td title="{$subscription.creator_contentobject_id|wash()}">

                                        {if is_object($subscription.creator)}
                                            {$subscription.creator.name|wash()}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Modified'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {if $subscription.modified|ne(0)}
                                            {$subscription.modified|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Modifier'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {if $subscription.modifier_contentobject_id|ne(0)}
                                            {if is_object( $subscription.modifier )}
                                                {$subscription.modifier.name|wash()}
                                            {/if}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Confirmed'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {if $subscription.confirmed|ne(0)}
                                            {$subscription.confirmed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Approved'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {if $subscription.approved|ne(0)}
                                            {$subscription.approved|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Inactived'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {if $subscription.inactived|ne(0)}
                                            {$subscription.inactived|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {'Bounced'|i18n('newsletter/user')}
                                    </th>
                                    <td>
                                        {if $subscription.bounced|ne(0)}
                                            {$subscription.bounced|l10n( shortdatetime )}
                                        {/if}
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
										{* Edit *}
										<div class="left">
											<form class="inline" action={concat( '/newsletter/user/', $subscription.id )|ezurl()} method="post">
												<input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
												<input  {if $subscription.can_be_approved}class="button"{else}class="button-disabled" disabled="disabled"{/if} type="submit" value="{'Approve'|i18n('newsletter/user')}" name="ApproveSubscriptionButton" title="{'Approve subscription'|i18n( 'newsletter/user' )}" />
											</form>
											<form class="inline" action={concat( 'newsletter/user/', $subscription.newsletter_user.id )|ezurl()} method="post">
												<input type="hidden" name="RedirectUrlActionCancel" value="{$page_uri}" />
												<input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
												<input class="button" type="submit" value="{'Edit'|i18n( 'newsletter/user' )}" title="{'Edit newsletter user'|i18n('newsletter/user')}" name="SubmitNewsletterUserButton" />
											</form>
												<form class="inline" action={concat( '/newsletter/user/', $subscription.id )|ezurl()} method="post">
													<input type="hidden" name="RedirectUrlActionCancel" value="{$page_uri}" />
													<input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
													<input  {if $subscription.can_be_inactived|not()}class="button-disabled" disabled="disabled"{else}class="button"{/if} type="submit" value="{'Remove'|i18n('newsletter/user')}" title="{'Remove subscription'|i18n('newsletter/user')}" name="RemoveSubscriptionButton" />
												</form>
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