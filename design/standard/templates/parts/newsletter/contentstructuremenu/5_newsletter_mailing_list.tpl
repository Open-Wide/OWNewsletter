{def $newsletter_mailing_list_node_id = $:newsletter_mailing_list_node.node_id}

    {let children       = array()
         numChildren    = array()
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
         toolTip        = ""
         visibility     = 'Visible'
         isRootNode     = false() }

        {default classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize', 'contentstructuremenu.ini' )
         last_item      = false() }

        {if is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/if}

        {if is_set($is_root_node)}
            {set isRootNode=false}
        {/if}

        <li id="nt{$newsletter_mailing_list_node_id}" {if $:last_item} class="lastli"{/if}>

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
                      onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>

            {* Label *}
                    {set toolTip = ''}


                {* icon *}
                {*<img src={'share/icons/crystal-admin/16x16_indexed/actions/view_tree.png'|ezroot} />*}
                {'newsletter_list'|class_icon( small )}

                {* Text *}
                {if or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
                    <a class="nodetext" href={$newsletter_mailing_list_node.url_alias|ezurl} title="{$:toolTip}"><span class="node-name-normal">{$:newsletter_mailing_list_node.name|wash}</span></a>
                {else}
                    <span class="node-name-normal">{$:newsletter_mailing_list_node.name|wash}</span>
                {/if}

                {* Show children *}

            <ul>
                {* subscription_list *}
                {*def $subcription_user_statistic = $:newsletter_mailing_list_node.data_map.newsletter_list.content.user_count_statistic*}
				{def $allSubcriptions = fetch( 'newsletter', 'subscriptions_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id
							) )}
                <li id="n{$newsletter_mailing_list_node_id}_subscription_list">
                    <span class="openclose"></span>
                    <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_user.png'|ezdesign} />
                    <a class="nodetext" href={$newsletter_mailing_list_node.url_alias|ezurl}>
                        <span class="node-name-normal">{'Subscriptions'|i18n('newsletter/contentstructuremenu')} ({$allSubcriptions})</span>
                    </a>
                </li>
				
				{def $pendingSubcriptions = fetch( 'newsletter', 'subscriptions_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'status', 'pending'
							) )}
                <li id="n{$newsletter_mailing_list_node_id}_pending_subscription_list">
                    <span class="openclose"></span>
                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_pending" />
                    <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/pending')|ezurl}>
                        <span class="node-name-normal">{'Pending'|i18n('newsletter/subscription/status')} ({$pendingSubcriptions})</span>
                    </a>
                </li>
				
				{def $confirmedSubcriptions = fetch( 'newsletter', 'subscriptions_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'status', 'confirmed'
							) )}
                <li id="n{$newsletter_mailing_list_node_id}_confirmed_subscription_list">
                    <span class="openclose"></span>
                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_confirmed" />
                    <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/confirmed')|ezurl}>
                        <span class="node-name-normal">{'Confirmed'|i18n('newsletter/subscription/status')} ({$confirmedSubcriptions})</span>
                    </a>
                </li>
				
				{def $approvedSubcriptions = fetch( 'newsletter', 'subscriptions_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'status', 'approved'
							) )}
                <li id="n{$newsletter_mailing_list_node_id}_approved_subscription_list">
                    <span class="openclose"></span>
                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_approved" />
                    <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/approved')|ezurl}>
                        <span class="node-name-normal">{'Approved'|i18n('newsletter/subscription/status')} ({$approvedSubcriptions})</span>
                    </a>
                </li>
				
				{def $bouncedSubcriptions = fetch( 'newsletter', 'subscriptions_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'status', 'bounced'
							) )}
                <li id="n{$newsletter_mailing_list_node_id}_bounced_subscription_list">
                    <span class="openclose"></span>
                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_bounced" />
                    <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/bounced')|ezurl}>
                        <span class="node-name-normal">{'Bounced'|i18n('newsletter/subscription/status')} ({$bouncedSubcriptions})</span>
                    </a>
                </li>
				
				{def $removedSubcriptions = fetch( 'newsletter', 'subscriptions_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'status', 'removed'
							) )}
                <li id="n{$newsletter_mailing_list_node_id}_removed_subscription_list">
                    <span class="openclose"></span>
                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_removed" />
                    <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/removed')|ezurl}>
                        <span class="node-name-normal">{'Removed'|i18n('newsletter/subscription/status')} ({$removedSubcriptions})</span>
                    </a>
                </li>
				
				{def $blacklistedSubcriptions = fetch( 'newsletter', 'subscriptions_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'status', 'blacklisted'
							) )}
                <li id="n{$newsletter_mailing_list_node_id}_blacklisted_subscription_list">
                    <span class="openclose"></span>
                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_blacklisted" />
                    <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/blacklisted')|ezurl}>
                        <span class="node-name-normal">{'Blacklisted'|i18n('newsletter/subscription/status')} ({$blacklistedSubcriptions})</span>
                    </a>
                </li>
                {undef}
            </ul>

        </li>
        {/default}
    {/let}
