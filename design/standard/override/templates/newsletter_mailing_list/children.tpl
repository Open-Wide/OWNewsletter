<div class="content-view-children">

	{* Generic children list for admin interface. *}
	{def $item_type    = ezpreference( 'admin_list_limit' )
     $number_of_items = min( $item_type, 3)|choose( 10, 10, 25, 50 )
     $can_remove   = false()
     $can_move     = false()
     $can_edit     = false()
     $can_create   = false()
     $can_copy     = false()
     $current_path = first_set( $node.path_array[1], 1 )
     $admin_children_viewmode = ezpreference( 'admin_children_viewmode' )
     $children_count = fetch( 'newsletter', 'subscriptions_count', hash(
				'mailing_list_contentobject_id', $node.contentobject_id
			) )
     $priority    = and( eq( $node.sort_array[0][0], 'priority' ), $node.can_edit, $children_count )
	 $status = $view_parameters.status}


	<!-- Children START -->

	<div class="context-block">
		<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
		<input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />

		<div class="box-header">

			<h2 class="context-title">
				<a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level.'|i18n(  'design/admin/node/view/full'  )}">
					<img src={'up-16x16-grey.png'|ezimage} width="16" height="16" alt="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" />
				</a>&nbsp{'Subscribers (%children_count)'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $children_count ) )}
			</h2>

		</div>

		<div class="box-content">


			{* Items per page and view mode selector. *}
			<div class="context-toolbar">
				<div class="button-left">
					<p class="table-preferences">
						{switch match=$limit}
						{case match=25}<a href={'/user/preferences/set/admin_user_list_limit/1'|ezurl}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
						<span class="current">25</span>
						<a href={'/user/preferences/set/admin_user_list_limit/3'|ezurl}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
						{/case}
						{case match=50}<a href={'/user/preferences/set/admin_user_list_limit/1'|ezurl}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
						<a href={'/user/preferences/set/admin_user_list_limit/2'|ezurl}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
						<span class="current">50</span>
						{/case}
						{case}<span class="current">10</span>
						<a href={'/user/preferences/set/admin_user_list_limit/2'|ezurl}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
						<a href={'/user/preferences/set/admin_user_list_limit/3'|ezurl}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
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
							<a href={$node.url_alias|ezurl}>
								{'All'|i18n('design/admin/node/view/full')}
							</a>
						{/if}

						{if $status|eq('pending')}
							<span class="current">
								<img src={'1x1.gif'|ezimage} alt="{'Pending'|i18n('newsletter/subscription/status')}" title="{'Pending'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_pending" /> {'Pending'|i18n('newsletter/subscription/status')}
							</span>
						{else}
							<a href={concat($node.url_alias, '/(status)/pending' )|ezurl}>
								<img src={'1x1.gif'|ezimage} alt="{'Pending'|i18n('newsletter/subscription/status')}" title="{'Pending'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_pending" /> {'Pending'|i18n('newsletter/subscription/status')}
							</a>
						{/if}

						{if $status|eq('confirmed')}
							<span class="current">
								<img src={'1x1.gif'|ezimage} alt="{'Confirmed'|i18n('newsletter/subscription/status')}" title="{'Confirmed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_confirmed" /> {'Confirmed'|i18n('newsletter/subscription/status')}
							</span>
						{else}
							<a href={concat($node.url_alias, '/(status)/confirmed' )|ezurl}>
								<img src={'1x1.gif'|ezimage} alt="{'Confirmed'|i18n('newsletter/subscription/status')}" title="{'Confirmed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_confirmed" /> {'Confirmed'|i18n('newsletter/subscription/status')}
							</a>
						{/if}
						{if $status|eq('approved')}
							<span class="current">
								<img src={'1x1.gif'|ezimage} alt="{'Approved'|i18n('newsletter/subscription/status')}" title="{'Approved'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_approved" /> {'Approved'|i18n('newsletter/subscription/status')}
							</span>
						{else}
							<a href={concat($node.url_alias, '/(status)/approved' )|ezurl}>
								<img src={'1x1.gif'|ezimage} alt="{'Approved'|i18n('newsletter/subscription/status')}" title="{'Approved'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_approved" /> {'Approved'|i18n('newsletter/subscription/status')}
							</a>
						{/if}
						{if $status|eq('bounced')}
							<span class="current">
								<img src={'1x1.gif'|ezimage} alt="{'Bounced'|i18n('newsletter/subscription/status')}" title="{'Bounced'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_bounced" /> {'Bounced'|i18n('newsletter/subscription/status')}
							</span>
						{else}
							<a href={concat($node.url_alias, '/(status)/bounced' )|ezurl}>
								<img src={'1x1.gif'|ezimage} alt="{'Bounced'|i18n('newsletter/subscription/status')}" title="{'Bounced'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_bounced" /> {'Bounced'|i18n('newsletter/subscription/status')}
							</a>
						{/if}
						{if $status|eq('removed')}
							<span class="current">
								<img src={'1x1.gif'|ezimage} alt="{'Removed'|i18n('newsletter/subscription/status')}" title="{'Removed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_removed" /> {'Removed'|i18n('newsletter/subscription/status')}
							</span>
						{else}
							<a href={concat($node.url_alias, '/(status)/removed' )|ezurl}>
								<img src={'1x1.gif'|ezimage} alt="{'Removed'|i18n('newsletter/subscription/status')}" title="{'Removed'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_removed" /> {'Removed'|i18n('newsletter/subscription/status')}
							</a>
						{/if}
						{if $status|eq('blacklisted')}
							<span class="current">
								<img src={'1x1.gif'|ezimage} alt="{'Blacklisted'|i18n('newsletter/subscription/status')}" title="{'Blacklisted'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_blacklisted" /> {'Blacklisted'|i18n('newsletter/subscription/status')}
							</span>
						{else}
							<a href={concat($node.url_alias, '/(status)/blacklisted' )|ezurl}>
								<img src={'1x1.gif'|ezimage} alt="{'Blacklisted'|i18n('newsletter/subscription/status')}" title="{'Blacklisted'|i18n('newsletter/subscription/status')}" class="icon12 icon_s_blacklisted" /> {'Blacklisted'|i18n('newsletter/subscription/status')}
							</a>
						{/if}
					</p>
				</div>
				<div class="float-break"></div>
			</div>

			{* Display the actual list of nodes. *}
			{include uri='design:children_detailed.tpl'}



			<div class="context-toolbar">
				{include name=navigator
             uri='design:navigator/alphabetical.tpl'
             page_uri=$node.url_alias
             item_count=$children_count
             view_parameters=$view_parameters
             node_id=$node.node_id
             item_limit=$number_of_items}
			</div>

		</div>

	</div>

	{* Load yui code for subitems display even if current node has no children (since cache blocks does not vary by this) *}
	{ezscript_require( array('ezjsc::yui2', 'ezajaxsubitems_datatable.js') )}

	<!-- Children END -->

	{undef $item_type $number_of_items $can_remove $can_move $can_edit $can_create $can_copy $current_path $admin_children_viewmode $children_count}
</div>
