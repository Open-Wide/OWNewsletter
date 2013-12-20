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
	{def $user_list_count = fetch('newsletter', 'user_count', hash( 
					'user_status', $view_parameters.status
				) )
		$base_uri = 'newsletter/user'
		$page_uri = $base_uri}
	{if $view_parameters.status|ne( '' )}
		{set $page_uri = concat( $page_uri, '/(status)/', $view_parameters.status )}
	{/if}
	{if $view_parameters.offset|gt( 0 )}
		{set $page_uri = concat( $page_uri, '/(offset)/', $view_parameters.offset )}
	{/if}

    <div class="context-block">
		{* DESIGN: Header START *}
		<div class="box-header">
			<div class="box-tc">
				<div class="box-ml">
					<div class="box-mr">
						<div class="box-tl">
							<div class="box-tr">
								<h1 class="context-title">{'Manage users'|i18n( 'newsletter/user' )|wash}</h1>
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
							<form action={$base_uri|ezurl()} name="UserList" method="post">
								<input type="text" name="SearchUserEmail" value="{if is_set($view_parameters['search_user_email'])}{$view_parameters['search_user_email']}{/if}">
								<input type="submit" name="SubmitUserSearch" value="{'Search for existing user'|i18n( 'newsletter/user' )}">
							</form>
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
										<form method="post" style="display:inline;" action={'newsletter/user'|ezurl()}>
											<input type="hidden" name="RedirectUrlActionCancel" value="{$page_uri}" />
											<input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
                                            <input class="button" type="submit" name="SubmitNewsletterUserButton" value="{'Create newsletter user'|i18n( 'newsletter/user' )}" />
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
                                <h2 class="context-title">{'Users'|i18n( 'newsletter/user' )} [{$user_list_count}]</h2>
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
                                {case match=25}<a href={'/user/preferences/set/admin_user_list_limit/1'|ezurl()}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                <span class="current">25</span>
                                <a href={'/user/preferences/set/admin_user_list_limit/3'|ezurl()}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
                                {/case}
                                {case match=50}<a href={'/user/preferences/set/admin_user_list_limit/1'|ezurl()}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                <a href={'/user/preferences/set/admin_user_list_limit/2'|ezurl()}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                <span class="current">50</span>
                                {/case}
                                {case}<span class="current">10</span>
                                <a href={'/user/preferences/set/admin_user_list_limit/2'|ezurl()}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                <a href={'/user/preferences/set/admin_user_list_limit/3'|ezurl()}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
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

								{if $status|eq('pending')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Pending'|i18n('newsletter/subscription/status')}" title="{'Pending'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_pending" /> {'Pending'|i18n('newsletter/subscription/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/pending' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Pending'|i18n('newsletter/subscription/status')}" title="{'Pending'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_pending" /> {'Pending'|i18n('newsletter/subscription/status')}
									</a>
								{/if}

								{if $status|eq('confirmed')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Confirmed'|i18n('newsletter/subscription/status')}" title="{'Confirmed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_confirmed" /> {'Confirmed'|i18n('newsletter/subscription/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/confirmed' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Confirmed'|i18n('newsletter/subscription/status')}" title="{'Confirmed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_confirmed" /> {'Confirmed'|i18n('newsletter/subscription/status')}
									</a>
								{/if}
								{if $status|eq('approved')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Approved'|i18n('newsletter/subscription/status')}" title="{'Approved'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_approved" /> {'Approved'|i18n('newsletter/subscription/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/approved' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Approved'|i18n('newsletter/subscription/status')}" title="{'Approved'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_approved" /> {'Approved'|i18n('newsletter/subscription/status')}
									</a>
								{/if}
								{if $status|eq('bounced')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Bounced'|i18n('newsletter/subscription/status')}" title="{'Bounced'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_bounced" /> {'Bounced'|i18n('newsletter/subscription/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/bounced' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Bounced'|i18n('newsletter/subscription/status')}" title="{'Bounced'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_bounced" /> {'Bounced'|i18n('newsletter/subscription/status')}
									</a>
								{/if}
								{if $status|eq('removed')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Removed'|i18n('newsletter/subscription/status')}" title="{'Removed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_removed" /> {'Removed'|i18n('newsletter/subscription/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/removed' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Removed'|i18n('newsletter/subscription/status')}" title="{'Removed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_removed" /> {'Removed'|i18n('newsletter/subscription/status')}
									</a>
								{/if}
								{if $status|eq('blacklisted')}
									<span class="current">
										<img src={'1x1.gif'|ezimage} alt="{'Blacklisted'|i18n('newsletter/subscription/status')}" title="{'Blacklisted'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_blacklisted" /> {'Blacklisted'|i18n('newsletter/subscription/status')}
									</span>
								{else}
									<a href={concat($base_uri, '/(status)/blacklisted' )|ezurl}>
										<img src={'1x1.gif'|ezimage} alt="{'Blacklisted'|i18n('newsletter/subscription/status')}" title="{'Blacklisted'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_blacklisted" /> {'Blacklisted'|i18n('newsletter/subscription/status')}
									</a>
								{/if}
							</p>
						</div>
						<div class="break float-break"></div>
					</div>
					{if $user_list_count}
						{def $user_list = fetch('newsletter', 'user_list', hash( 
							'user_status', $view_parameters.status,
							'offset', $view_parameters.offset,
							'limit', $limit
						) )}
						<div class="content-navigation-childlist overflow-table">
							<table class="list" cellspacing="0">
								<tr>
									<th class="tight">
										{'Id'|i18n('newsletter/user')}</a>
									</th>
									<th>
										{'Email'|i18n( 'newsletter/user' )}
									</th>
									<th>
										{'Mailing lists'|i18n( 'newsletter/user' )}
									</th>
									<th>
										{'Confirmed'|i18n( 'newsletter/user' )}
									</th>
									<th>
										{'Blacklisted'|i18n( 'newsletter/user' )}
									</th>
									<th>
										{'Bounce'|i18n( 'newsletter/user' )}
									</th>
									<th>
										{'Status'|i18n( 'newsletter/user' )}
									</th>
									<th>
										{'eZ user id'|i18n( 'newsletter/user' )}
									</th>
									<th class="edit">
									</th>
								</tr>
								{foreach $user_list as $newsletter_user sequence array( bglight, bgdark ) as $style}
									<tr class="{$style}">
										<td class="number" align="right">
											<a href={concat('newsletter/user/',$newsletter_user.id)|ezurl()} title="{'Newsletter user id'|i18n( 'newsletter/user' )}">{$newsletter_user.id} </a>
										</td>
										<td>
											<a href={concat('newsletter/user/',$newsletter_user.id)|ezurl()} title="{$newsletter_user.first_name} {$newsletter_user.last_name}">{$newsletter_user.email|wash}</a>
										</td>
										<td title="{'Approved'|i18n( 'newsletter/subscription/status' )} / {'All'|i18n( 'newsletter/user' )}">
											{def $approved_subscribtion_count = 0
												 $subscription_array = $newsletter_user.subscription_array}
											{foreach $subscription_array as $subscription}
												{if $subscription.status|eq( 2 )}
													{set $approved_subscribtion_count = $approved_subscribtion_count|inc}
												{/if}

											{/foreach}
											<b>{$approved_subscribtion_count}</b> / {$subscription_array|count}
											{undef $approved_subscribtion_count $subscription_array}
										</td>
										<td {cond($newsletter_user.confirmed|gt(0), concat('title="',$newsletter_user.confirmed|l10n(  shortdatetime ),'"') ,  '' )}>
											{cond($newsletter_user.confirmed|gt(0), 'x', '-')}
										</td>
										<td>
											{cond($newsletter_user.blacklisted|gt(0),'x' , '-' )}
										</td>
										<td title="{'Bounced'|i18n( 'newsletter/subscription/status' )} / {'Bounce count'|i18n( 'newsletter/user' )}">
											{cond($newsletter_user.bounced|gt(0),'x' , '-' )} / {$newsletter_user.bounce_count|wash}
										</td>
										<td title="{$newsletter_user.status|wash}">
											{$newsletter_user.status_name|wash}
										</td>
										<td>
											{cond($newsletter_user.ez_user_id|gt(0), $newsletter_user.ez_user_id , '-' )}
										</td>
										<td>
											<form method="post" style="display:inline;" action={concat( 'newsletter/user/', $newsletter_user.id)|ezurl()}>
												<input type="hidden" name="RedirectUrlActionCancel" value="{$page_uri}" />
												<input type="hidden" name="RedirectUrlActionSuccess" value="{$page_uri}" />
												<button class="button image-button" type="submit" name="SubmitNewsletterUserButton" value="{'Edit newsletter user'|i18n( 'newsletter/user' )}">
													<img title="{'Edit newsletter user'|i18n( 'newsletter/user' )}" alt="{'Edit newsletter user'|i18n( 'newsletter/user' )}" src={'edit.gif'|ezimage()} />
												</button>
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
                             item_count=$user_list_count
                             view_parameters=$view_parameters
                             item_limit=$limit}
						</div>
					{else}
						<p>{'No user'|i18n('newsletter/user')}</p>
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