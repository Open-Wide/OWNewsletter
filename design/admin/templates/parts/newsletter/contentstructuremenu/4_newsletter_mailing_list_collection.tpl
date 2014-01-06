{let $newsletter_root_node_id = $newsletter_mailing_list_collection_node.node_id
	 children       = fetch( 'content', 'tree', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array',
				array( 'newsletter_mailing_list' ) 
			) )
	 numChildren    = fetch( 'content', 'tree_count', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array',
				array('newsletter_mailing_list') 
			) )
	 haveChildren   = $numChildren|gt(0)
	 showToolTips   = ezini( 'TreeMenu', 'ToolTips' , 'contentstructuremenu.ini' )
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
		{set isRootNode=$is_root_node}
	{/if}

	<li id="n0_{$newsletter_mailing_list_collection_node.node_id}" {cond( $:last_item, 'class="lastli"', '' )}>

		{* Fold/Unfold/Empty: [-]/[+]/[ ] *}
			   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
				  onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>

		{* Label *}
				{set toolTip = ''}

			{* Text *}

			{* icon *}
			{'newsletter_mailing_list_collection'|class_icon( small )}

			{if or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
				<a class="nodetext" href={$newsletter_mailing_list_collection_node.url_alias|ezurl} title="{$:toolTip}"><span class="node-name-normal">{$newsletter_mailing_list_collection_node.name}</span></a>
			{else}
				<span class="node-name-normal">{$newsletter_mailing_list_collection_node.name|wash}</span>
			{/if}

			{* Show children *}
			{if $:haveChildren}
				<ul>
					{foreach $:children as $:child}
						{include name=SubMenu uri="design:parts/newsletter/contentstructuremenu/5_newsletter_mailing_list.tpl" newsletter_mailing_list_node=$:child csm_menu_item_click_action=$:csm_menu_item_click_action last_item=eq( $child.number, $:numChildren ) ui_context=$ui_context}
					{/foreach}
				</ul>
			{/if}
	</li>
	{/default}
{/let}
