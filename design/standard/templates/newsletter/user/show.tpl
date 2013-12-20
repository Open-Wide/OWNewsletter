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
                                    <th>{'Email'|i18n( 'newsletter/user' )}</th>
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
                                    <th>{'Organisation'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.organisation|wash()}</td>
                                </tr>
                                <tr>
                                    <th>{'Status'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.status_name|wash()}</td>
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
                                    <th>{'Import id'|i18n( 'newsletter/user' )}</th>
                                    <td>{$newsletter_user.import_id|wash()}</td>
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
											{if $newsletter_user.status|ne(8)}
												<form id="submit_newsletter_user" method="post" style="display:inline;" action={concat( 'newsletter/user/', $newsletter_user.id)|ezurl()}>
													<input type="hidden" name="RedirectUrlActionCancel" value={concat( 'newsletter/user/', $newsletter_user.id)|ezurl()} />
													<input type="hidden" name="RedirectUrlActionSuccess" value={concat( 'newsletter/user/', $newsletter_user.id)|ezurl()} />
													<input class="button" type="submit" name="SubmitNewsletterUserButton" value={'Edit'|i18n( 'newsletter/user' )} />
												</form>
											{else}
												<input class="button-disabled" type="submit" value="{'Edit'|i18n( 'newsletter/user' )}" title="{'Edit'|i18n( 'newsletter/user' )}" />
											{/if}
											<form id="delete_newsletter_user" method="post" style="display:inline;" action={concat( 'newsletter/user/', $newsletter_user.id)|ezurl()}  onsubmit="return confirm('{'Do you really want to delete this user?'|i18n( 'newsletter/user' )|wash()}');">
												<input type="hidden" name="RedirectUrlActionRemove" value={'newsletter/user'|ezurl()} />
												<input class="button" type="submit" name="RemoveNewsletterUserButton" value={'Remove'|i18n( 'newsletter/user' )} />
											</form>
											{if $newsletter_user.status|ne(8)}
												<form id="blacklist_newsletter_user" method="post" style="display:inline;" action={'newsletter/blacklist'|ezurl}>
													<input type="hidden" name="Email" value="{$newsletter_user.email|wash()}" />
													<input disabled="disabled" class="button" type="submit" name="AddToBlacklistButton" value="{'Add to blacklist'|i18n( 'newsletter/user' )}" />
												</form>
											{else}
												<form id="unblacklist_newsletter_user" method="post" style="display:inline;" action={'newsletter/blacklist'|ezurl}>
													<input type="hidden" name="Email" value="{$newsletter_user.email|wash()}" />
													<input disabled="disabled" class="button" type="submit" name="RemoveFromBlacklistButton" value="{'Remove from blacklist'|i18n( 'newsletter/user' )}" />
												</form>
											{/if}

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>{* =============  list of all subscriptions ============ *}
		<div class="context-block">
			{def $subscription_array = $newsletter_user.subscription_array
             $subscription_array_count = $subscription_array|count}
			<div class="box-header">
				<div class="box-tc">
					<div class="box-ml">
						<div class="box-mr">
							<div class="box-tl">
								<div class="box-tr">
									<h2 class="context-title">{'Subscriptions'|i18n( 'newsletter/user' )} [{$subscription_array_count}]</h2>
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
											{'Confirmed'|i18n( 'newsletter/user' )}
										</th>
										<th>
											{'Approved'|i18n( 'newsletter/user' )}
										</th>
										<th>
											{'Removed'|i18n( 'newsletter/user' )}
										</th>
										<th>
											{* actions *}
										</th>
									</tr>
									{foreach $subscription_array as $subscription sequence array( bglight, bgdark ) as $style}
										<tr class="{$style}">
											<td>
												<a href={$subscription.mailing_list.main_node.url_alias|ezurl}>{$subscription.mailing_list.name|wash()} </a>
											</td>
											<td title="{$subscription.status|wash()}">
												{$subscription.status_name|wash()}
											</td>
											<td>
												{cond( $subscription.created|gt(0), $subscription.created|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
											</td>
											<td>
												{cond( $subscription.modified|gt(0), $subscription.modified|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
											</td>
											<td>
												{cond( $subscription.confirmed|gt(0), $subscription.confirmed|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
											</td>
											<td>
												{cond( $subscription.approved|gt(0), $subscription.approved|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
											</td>
											<td>
												{cond( $subscription.removed|gt(0), $subscription.removed|l10n( shortdatetime ), 'n/a'|i18n( 'newsletter/user' ) )}
											</td>
											<td>
												<span>Bouton pour approuver la souscription</span><br />
												<span>Bouton pour supprimer la souscription</span>
											</td>
										</tr>
									{/foreach}
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

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{* =============  list of all send items ============ *}

		{*<div class="context-block">
		{def $edition_send_item_array = fetch( 'newsletter', 'edition_send_item_list', hash('newsletter_user_id', $newsletter_user.id ) )
		$edition_send_item_count = fetch( 'newsletter', 'edition_send_item_list_count', hash('newsletter_user_id', $newsletter_user.id ) )}

		<div class="box-header">
		<div class="box-tc">
		<div class="box-ml">
		<div class="box-mr">
		<div class="box-tl">
		<div class="box-tr">
		<h2 class="context-title">{'Newsletter received'|i18n( 'newsletter/user',, hash() )} [{$edition_send_item_count}]</h2>
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
		<div class="overflow-table">
		<table class="list" cellspacing="0">
		<tr>
		<th class="tight">
		{'Id'|i18n( 'newsletter/user' )}
		</th>
		<th>
		{'Edition sent id'|i18n( 'newsletter/user' )}
		</th>
		<th>
		{'Format'|i18n( 'newsletter/user' )}
		</th>
		<th>
		{'Status'|i18n( 'newsletter/user' )}
		</th>
		<th>
		{'Created'|i18n( 'newsletter/user' )}
		</th>
		<th>
		{'Processed'|i18n( 'newsletter/user' )}
		</th>
		<th>
		{'Opened'|i18n( 'newsletter/user' )}
		</th>
		<th>
		{'Bounced'|i18n( 'newsletter/user' )}
		</th>
		</tr>
		{foreach $edition_send_item_array as $send_item sequence array( bglight, bgdark ) as $style}
		<tr class="{$style}">
		<td>
		<a target="_blank" href={concat( 'newsletter/preview_archive/', $send_item.edition_send_id,  '/', $send_item.output_format_id,  '/', $send_item.newsletter_user_id )|ezurl}>{$send_item.id|wash()} </a>
		</td>
		<td>
		{$send_item.edition_send_id|wash()}
		</td>
		<td>
		{$send_item.output_format_id|wash()}
		</td>
		<td title="{$send_item.status|wash()}">
		{$send_item.status_name|wash()}
		</td>
		<td>
		{cond( $send_item.created|gt(0), $send_item.created|l10n( shortdatetime ), '-' )}
		</td>
		<td>
		{cond( $send_item.processed|gt(0), $send_item.processed|l10n( shortdatetime ), '-' )}
		</td>
		<td>
		-
		</td>
		<td>
		{cond( $send_item.bounced|gt(0), $send_item.bounced|l10n( shortdatetime ), '-' )}
		</td>
		</tr>
		{/foreach}
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

		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>*}
	</div>
</div>