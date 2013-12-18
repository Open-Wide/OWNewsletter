<div id="action-controls-container">
	<div id="action-controls">
		<form name="CreateNewNewsletterUser" method="post" style="display:inline;" action={'newsletter/user_create'|ezurl}>
			<input type="hidden" name="Subscription_IdArray[]" value="{$node.contentobject_id}" />
			<input type="hidden" name="Subscription_ListArray[]" value="{$node.contentobject_id}" />
			<input type="hidden" name="RedirectUrlActionCancel" value="{$node.url_alias}" />
			<input type="hidden" name="RedirectUrlActionStore" value="{$node.url_alias}" />
			<input class="button" type="submit" name="NewSubscriptionButton" value="{'Create new subscription'|i18n( 'design/admin/node/view/full' )}" />
		</form>
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
		{*def $user_list = fetch('newsletter', 'user_list_search',
		hash( 'search_str', $view_parameters.search_user_email,
		'logic', 'AND',
		'offset', $view_parameters.offset,
		'limit', $limit,
		
		'sort_by', hash('created', 'desc' ) ))
		$user_list_count = fetch('newsletter', 'user_list_search',
		hash( 'search_str', $view_parameters.search_user_email,
		'logic', 'AND',
		'offset', $view_parameters.offset,
		'as_object', false(),
		'sort_by', hash('created', 'desc' ) ))|count
		$page_uri = 'newsletter/user'*}
		{def $user_list = fetch( 'newsletter', 'user_list', hash(
					'mailing_list_contentobject_id', $node.contentobject_id
				) )
		  $user_list_count  = fetch( 'newsletter', 'user_count', hash(
					'mailing_list_contentobject_id', $node.contentobject_id
				) )
		  $page_uri = 'newsletter/user'}

		{*<div class="context-block">
		<div class="box-header">
		<div class="box-tc">
		<div class="box-ml">
		<div class="box-mr">
		<div class="box-tl">
		<div class="box-tr">
		<h1 class="context-title">{'Manage users'|i18n(  'design/admin/node/view/full' ,, hash() )|wash}</h1>
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
		<form action={$page_uri|ezurl} name="UserList" method="post">
		<input type="text" name="SearchUserEmail" value="{if is_set($view_parameters['search_user_email'])}{$view_parameters['search_user_email']}{/if}"><input type="submit" name="SubmitUserSearch" value="{'Search for existing user'|i18n(  'design/admin/node/view/full'  )}">
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
		<form method="post" style="display:inline;" action={'newsletter/user_create/-1'|ezurl}>
		<input class="button" type="submit" name="CreateNewsletterUserButton" value="{'Create Newsletter user'|i18n(  'design/admin/node/view/full'  )}" />
		</form>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>*}
		<div class="context-block">
			<div class="box-ml">
				<div class="box-mr">
					<div class="box-content">

						<div class="context-toolbar">
						</div>
						<div class="break float-break">
						</div>
						{if $user_list_count}
						<div class="content-navigation-childlist overflow-table">

							<table class="list" cellspacing="0">
								<tr>
									<th class="tight">
										{'UID'|i18n('cjw_newsletter/user')}
									</th>
									<th>
										{'Email'|i18n(  'design/admin/node/view/full'  )}
									</th
									<th>
										{'Lists'|i18n(  'design/admin/node/view/full'  )}
									</th>
									<th title="{'Confirmed'|i18n(  'design/admin/node/view/full'  )}">
										{'Conf'|i18n(  'design/admin/node/view/full'  )}
									</th>
									<th title="{'Blacklisted'|i18n(  'design/admin/node/view/full'  )}">
										{'Black'|i18n(  'design/admin/node/view/full'  )}
									</th>
									<th>
										{'Bounce'|i18n(  'design/admin/node/view/full'  )}
									</th>
									<th>
										{'Status'|i18n(  'design/admin/node/view/full'  )}
									</th>
									<th>
										{'eZ user id'|i18n(  'design/admin/node/view/full'  )}
									</th>
									<th class="edit">
										{* user_edit *}
									</th>
								</tr>
								{foreach $user_list as $newsletter_user sequence array( bglight, bgdark ) as $style}
									<tr class="{$style}">
										<td class="number" align="right">
											<a href={concat('newsletter/user_view/',$newsletter_user.id)|ezurl} title="{'Newsletter user id'|i18n(  'design/admin/node/view/full'  )}">{$newsletter_user.id} </a>
										</td>
										<td>
											<a href={concat('newsletter/user_view/',$newsletter_user.id)|ezurl} title="{$newsletter_user.first_name} {$newsletter_user.last_name}">{$newsletter_user.email|wash}</a>
										</td>
										<td title="{'Approved'|i18n(  'design/admin/node/view/full'  )} / {'All'|i18n(  'design/admin/node/view/full'  )}">
											{def $approved_subscribtion_count = 0
                                     $subscription_array = $newsletter_user.subscription_array}
											{foreach $subscription_array as $subscription}
												{*if approved*}
												{if $subscription.status|eq( 2 )}
													{set $approved_subscribtion_count = $approved_subscribtion_count|inc}
												{/if}

											{/foreach}
											<b>{$approved_subscribtion_count}</b> / {$subscription_array|count}
											{undef $approved_subscribtion_count $subscription_array}
										</td>
										<td {cond($newsletter_user.confirmed|gt(0), concat('title="',$newsletter_user.confirmed|l10n(  shortdatetime ),'"') ,  '' )}>
											{cond($newsletter_user.confirmed|gt(0), 'x', '-')}{*$newsletter_user.confirmed|l10n(shortdatetime)*}
										</td>
										<td>
											{cond($newsletter_user.blacklisted|gt(0),'x' , '-' )}
										</td>
										<td title="{'Bounced'|i18n(  'design/admin/node/view/full'  )} / {'Bounce count'|i18n(  'design/admin/node/view/full'  )}">
											{cond($newsletter_user.bounced|gt(0),'x' , '-' )} / {$newsletter_user.bounce_count|wash}
										</td>
										<td title="{$newsletter_user.status|wash}">
											{$newsletter_user.status_string|wash}
										</td>
										<td>
											{cond($newsletter_user.ez_user_id|gt(0), $newsletter_user.ez_user_id , '-' )}
										</td>
										<td>
											<a href={concat( 'newsletter/user_edit/', $newsletter_user.id, '?RedirectUrl=newsletter/user/(offset)/', $view_parameters.offset )|ezurl}>
												<img title="{'Edit newsletter user'|i18n(  'design/admin/node/view/full'  )}" alt="{'Edit newsletter user'|i18n(  'design/admin/node/view/full'  )}" src={'edit.gif'|ezimage()} />
											</a>
										</td>

									</tr>{/foreach}
								</table>
							</div>

							{* Navigator. *}
							<div class="context-toolbar subitems-context-toolbar">
								{include name='Navigator'
                             uri='design:navigator/google.tpl'
                             page_uri=$page_uri
                             item_count=$user_list_count
                             view_parameters=$view_parameters
                             item_limit=$limit}
							</div>
							{else}
								<p>{'No subscriber'|i18n(  'design/admin/node/view/full'  )}</p>
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