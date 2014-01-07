<div class="newsletter newsletter-mailbox_list">
	{def $limit = 50}
	{if ezpreference( 'admin_mailbox_list_limit' )}
		{switch match=ezpreference( 'admin_mailbox_list_limit' )}
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
	{def $base_uri = 'newsletter/mailbox'
		$page_uri = $base_uri
		$status = $view_parameters.status}
	{if $view_parameters.status|ne( '' )}
		{set $page_uri = concat( $page_uri, '/(status)/', $view_parameters.status )}
	{/if}
	{if $view_parameters.offset|gt( 0 )}
		{set $page_uri = concat( $page_uri, '/(offset)/', $view_parameters.offset )}
	{/if}
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Mailboxes'|i18n( 'newsletter/mailbox' )} [{$mailbox_list_count}/{$all_mailbox_list_count}]</h2>
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
                    <div class="context-toolbar">
                        <div class="button-left">
                            <p class="table-preferences">
                                {switch match=$limit}
                                {case match=25}<a href={'/user/preferences/set/admin_mailbox_list_limit/1'|ezurl()}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                <span class="current">25</span>
                                <a href={'/user/preferences/set/admin_mailbox_list_limit/3'|ezurl()}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
                                {/case}
                                {case match=50}<a href={'/user/preferences/set/admin_mailbox_list_limit/1'|ezurl()}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                <a href={'/user/preferences/set/admin_mailbox_list_limit/2'|ezurl()}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                <span class="current">50</span>
                                {/case}
                                {case}<span class="current">10</span>
                                <a href={'/user/preferences/set/admin_mailbox_list_limit/2'|ezurl()}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                <a href={'/user/preferences/set/admin_mailbox_list_limit/3'|ezurl()}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
                                {/case}
                                {/switch}
                            </p>
                        </div>
						<div class="button-right">
							{* newsletter list selection *}
							<p class="table-preferences">
								{if $status|eq('')}
									<span class="current">
										{'All'|i18n('design/admin/node/view/full')}
									</span>
								{else}
									<a href={$base_uri|ezurl}>
										{'All'|i18n('design/admin/node/view/full')}
									</a>
								{/if}

								{if $status|eq('active')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Active'|i18n('newsletter/mailbox/status')}" title="{'Active'|i18n('newsletter/mailbox/status')}" class="icon12 icon_mb_active" /> {'Active'|i18n('newsletter/mailbox/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/active' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Active'|i18n('newsletter/mailbox/status')}" title="{'Active'|i18n('newsletter/mailbox/status')}" class="icon12 icon_mb_active" /> {'Active'|i18n('newsletter/mailbox/status')}
									</a>
								{/if}

								{if $status|eq('inactive')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Inactive'|i18n('newsletter/mailbox/status')}" title="{'Inactive'|i18n('newsletter/mailbox/status')}" class="icon12 icon_mb_inactive" /> {'Inactive'|i18n('newsletter/mailbox/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/inactive' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Inactive'|i18n('newsletter/mailbox/status')}" title="{'Inactive'|i18n('newsletter/mailbox/status')}" class="icon12 icon_mb_inactive" /> {'Inactive'|i18n('newsletter/mailbox/status')}
									</a>
								{/if}
							</p>
						</div>
						<div class="break float-break"></div>
					</div>
					<div class="user_search">
						<div class="block float-break">
							<div class="left">
								<form method="post" style="display:inline;" action={'newsletter/mailbox'|ezurl()}>
									<input type="hidden" name="RedirectUrlActionCancel" value="{$page_uri}" />
									<input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
									<input class="button" type="submit" name="SubmitMailboxButton" value="{'Create mailbox'|i18n( 'newsletter/mailbox' )}" />
								</form>
							</div>
						</div>
					</div>
					<div class="break float-break"></div>
					{if $mailbox_list_count}
						<div class="content-navigation-childlist overflow-table">
							<table class="list" cellspacing="0">
								<tr>
									<th>
										{'E-mail'|i18n( 'newsletter/mailbox' )}
									</th>
									<th>
										{'Server'|i18n( 'newsletter/mailbox' )}
									</th>
									<th>
										{'Port'|i18n( 'newsletter/mailbox' )}
									</th>
									<th>
										{'User'|i18n( 'newsletter/mailbox' )}
									</th>
									<th>
										{'Type'|i18n( 'newsletter/mailbox' )}
									</th>
									<th>
										{'SSL'|i18n( 'newsletter/mailbox' )}
									</th>
									<th>
										{'Delete e-mails from server'|i18n( 'newsletter/mailbox' )}
									</th>
									<th>
										{'Last connexion'|i18n( 'newsletter/mailbox' )}
									</th>
									<th class="edit">
									</th>
								</tr>
								{foreach $mailbox_list as $mailbox sequence array( bglight, bgdark ) as $style}
									<tr class="{$style}">
										<td>
											{if $mailbox.is_activated}
												<img src={'16x16.gif'|ezimage} alt="{'Active'|i18n('newsletter/mailbox/status')}" title="{'Active'|i18n('newsletter/mailbox/status')}" class="icon12 icon_mb_active" />
											{else}
												<img src={'16x16.gif'|ezimage} alt="{'Active'|i18n('newsletter/mailbox/status')}" title="{'Active'|i18n('newsletter/mailbox/status')}" class="icon12 icon_mb_inactive" />
											{/if}
											<a href={concat('newsletter/mailbox/',$mailbox.id)|ezurl()} title="{$mailbox.email}">{$mailbox.email|wash}</a>
										</td>
										<td>
											{$mailbox.server|wash}
										</td>
										<td>
											{$mailbox.port|wash}
										</td>
										<td>
											{$mailbox.username|wash}
										</td>
										<td>
											{$mailbox.type|wash}
										</td>
										<td>
											{if $mailbox.is_ssl}
												{'Yes'|i18n( 'newsletter/mailbox' )}
											{else}
												{'No'|i18n( 'newsletter/mailbox' )}
											{/if}
										</td>
										<td>
											{if $mailbox.delete_mails_from_server}
												{'Yes'|i18n( 'newsletter/mailbox' )}
											{else}
												{'No'|i18n( 'newsletter/mailbox' )}
											{/if}
										</td>
										<td>
											{if $mailbox.last_server_connect}
											{$mailbox.last_server_connect|l10n('shortdatetime')}
											{else}
												{'n/a'|i18n( 'newsletter/mailbox' )}
											{/if}
										</td>
										<td>
											<form id="submit_mailbox_{$mailbox.id}" method="post" style="display:inline;" action={concat( 'newsletter/mailbox/', $mailbox.id)|ezurl()}>
												<input type="hidden" name="RedirectUrlActionCancel" value={concat( 'newsletter/mailbox/', $mailbox.id)|ezurl()} />
												<input type="hidden" name="RedirectUrlActionSuccess" value={concat( 'newsletter/mailbox/', $mailbox.id)|ezurl()} />
												<input class="button" type="submit" name="SubmitMailboxButton" value="{'Edit'|i18n( 'newsletter/mailbox' )}" />
												<input {if $mailbox.is_activated|not()}class="button-disabled"{else}class="button"{/if} 
																				type="submit" name="DisableMailboxButton" value="{'Disable'|i18n( 'newsletter/mailbox' )}" />
												<input {if $mailbox.is_activated}class="button-disabled"{else}class="button"{/if} 
																			   type="submit" name="EnableMailboxButton" value="{'Enable'|i18n( 'newsletter/mailbox' )}" />
												<input class="button" type="submit" name="RemoveMailboxButton" value="{'Remove'|i18n( 'newsletter/mailbox' )}" onclick="return confirm('{'Do you really want to delete this mailbox?'|i18n( 'newsletter/mailbox' )|wash()}');" />
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
                             page_uri=$base_uri
                             item_count=$all_mailbox_list_count
                             view_parameters=$view_parameters
                             item_limit=$limit}
						</div>
					{else}
						<p>{'No mailbox'|i18n('newsletter/mailbox')}</p>
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
{undef}