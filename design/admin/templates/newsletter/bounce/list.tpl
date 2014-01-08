{def $limit = 50}
{if ezpreference( 'admin_bounce_list_limit' )}
	{switch match=ezpreference( 'admin_bounce_list_limit' )}
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
{def $page_uri = 'newsletter/bounce'}
<div class="newsletter newsletter-bounce_list">
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Bounces'|i18n( 'newsletter/bounce' )}</h1>
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
                        <p>
							{'Collect emails from bounce accounts and parse them. You may then accept the detected bounce status or manually adjust it.'|i18n( 'newsletter/bounce' )}
                        </p>
                        <div class="block float-break">
                            {if is_set( $collect_mail_result )}<h3>{'Mailbox collect mail result'|i18n( 'newsletter/bounce' )}</h3>
								{foreach $collect_mail_result as $mailbox_id => $bounces_status}
									{'Collection result for mailbox %mailbox_id.'|i18n( 'newsletter/bounce',, hash( '%mailbox_id', $mailbox_id ) )}
									<ul>
										{if is_array($collect_mail_result[$mailbox_id])}
											<li>
												{'Added'|i18n('newsletter/bounce')}: {$collect_mail_result[$mailbox_id]['added']|count()}
											</li>
											<li>
												{'Already exists'|i18n('newsletter/bounce')}: {$collect_mail_result[$mailbox_id]['exists']|count()}
											</li>
											<li>
												{'Failed'|i18n('newsletter/bounce')}: {$collect_mail_result[$mailbox_id]['failed']|count()}
											</li>
										{else}
											<li>
												{'Connection failed'|i18n('newsletter/bounce')}
											</li>
										{/if}
									</ul>
								{/foreach}
                            {/if}
                            {if is_set( $parse_result )}<h3>{'Mailbox item parse result'|i18n( 'newsletter/bounce',, hash() )}</h3>
								{'E-mails parsed'|i18n('newsletter/bounce')}: {$parse_result|count() }
                            {/if}
                        </div>
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
                                        <form action={$page_uri|ezurl} name="connectMailbox" method="post">
                                            <input type="submit" class="button" name="ConnectMailboxButton" value="{'Collect all mailboxes'|i18n( 'newsletter/bounce' )}">
                                            <input type="submit" class="button" name="ParseEmailsButton" value="{'Parse e-mails'|i18n( 'newsletter/bounce' )}">
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
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Mailbox items'|i18n( 'newsletter/bounce' )} [{$bounce_list_count}/{$all_bounce_list_count}]</h2>
                                <div class="header-subline"></div>
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

                        <div class="context-toolbar">
                            <div class="button-left">
                                <p class="table-preferences">
									{switch match=$limit}
                                    {case match=25}
									<a href={'/user/preferences/set/admin_bounce_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
									<span class="current">25</span>
									<a href={'/user/preferences/set/admin_bounce_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                                    {/case}
                                    {case match=50}
									<a href={'/user/preferences/set/admin_bounce_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
									<a href={'/user/preferences/set/admin_bounce_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
									<span class="current">50</span>
                                    {/case}
                                    {case}
									<span class="current">10</span>
									<a href={'/user/preferences/set/admin_bounce_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
									<a href={'/user/preferences/set/admin_bounce_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                                    {/case}
									{/switch}
                                </p>
                            </div>
                        </div>
                        <div class="break float-break">
                        </div>

                        <div class="content-navigation-childlist overflow-table">
                            <table class="list" cellspacing="0">
                                <tr>
									<th>
										{'Message identifier'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'Message size'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'Bounce code'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'Bounce'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'User'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'Subject'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'From'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'To'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'Sending date'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'Created'|i18n( 'newsletter/bounce' )}
									</th>
									<th>
										{'Processed'|i18n( 'newsletter/bounce' )}
									</th>
                                </tr>
                                {foreach $bounce_list as $bounce sequence array( bglight, bgdark ) as $style}
									{if $bounce.is_system_bounce}
										<tr class="{$style}" style="background-color:#FFF6BF;">
										{else}
										<tr class="{$style}">
										{/if}
										<td>
											{$bounce.message_identifier|wash|shorten(50)}
										</td>
										<td>
											{*$bounce.message_size|si( 'byte', 'kilo' )*}
											{$bounce.message_size|div( 1024 )|round()} kB
										</td>
										<td>
											{$bounce.bounce_code|wash}
										</td>
										<td>
											{if $bounce.is_bounce|eq(true())}x{else}-{/if}
										</td>
										<td>
											{if $bounce.newsletter_user_id|ne( 0 )}
												<a href={concat('newsletter/user/',$bounce.newsletter_user_id)|ezurl}>{$bounce.newsletter_user_id|wash} </a>
											{/if}
										</td>
										<td>
											<span title="{$bounce.email_subject|wash()}">{$bounce.email_subject|wash|shorten(20)}</span>
										</td>
										<td>
											<span title="{$bounce.email_from|wash()}">{$bounce.email_from|wash|shorten(20)}</span>
										</td>
										<td>
											<span title="{$bounce.email_to|wash()}">{$bounce.email_to|wash|shorten(20)}</span>
										</td>
										<td>
											{if eq( $bounce.sending_date, 0 )}
												{'n/a'|i18n( 'newsletter/bounce' )}
											{else}
												<span title="{$bounce.sending_date|l10n( shortdatetime )|wash()}">{$$bounce.sending_date|l10n( shortdatetime )|wash|shorten(10, '')}</span>
											{/if}
										</td>
										<td>
											<span title="{$bounce.created|l10n( shortdatetime )|wash()}">{$$bounce.created|l10n( shortdatetime )|wash|shorten(10, '')}</span>
										</td>
										<td>
											{if eq( $bounce.processed, 0 )}
												{'n/a'|i18n( 'newsletter/bounce' )}
											{else}
												<span title="{$bounce.processed|l10n( shortdatetime )|wash()}">{$$bounce.processed|l10n( shortdatetime )|wash|shorten(10, '')}</span>
											{/if}
										</td>
									</tr>{/foreach}
								</table>
							</div>
						</div>

						<div class="context-toolbar subitems-context-toolbar">
							<div class="context-toolbar">
								{include name='Navigator'
									uri='design:navigator/google.tpl'
									page_uri=$page_uri
									item_count=$bounce_list_count
									view_parameters=$view_parameters
									item_limit=$limit}
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
	</div>
