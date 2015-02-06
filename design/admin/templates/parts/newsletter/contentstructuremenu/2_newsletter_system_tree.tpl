{def $newsletter_root_node_id = ezini( 'NewsletterSettings', 'RootFolderNodeId', 'newsletter.ini' )
	 $children       = fetch( 'content', 'list', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array', array('newsletter_system'),
				'sort_by', array( 'name', true() )
			) )
	 $num_children    = fetch( 'content', 'list_count', hash(
			'parent_node_id', $newsletter_root_node_id,
			'class_filter_type', 'include',
			'class_filter_array',
			array('newsletter_system')
			) )
	 $have_children   = $num_children|gt(0)
	 $show_tooltips   = ezini( 'TreeMenu', 'ToolTips' , 'contentstructuremenu.ini' )
	 $translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
	 $tooltip        = ""
	 $visibility     = 'Visible' }


{if is_set($class_icons_size)}
    {set $class_icons_size=$class_icons_size}
{/if}

{if is_set($is_root_node)}
    {set $is_root_node=$is_root_node}
{/if}

<li id="n0_{$newsletter_root_node_id}" {cond( $:last_item, 'class="lastli"', '' )}>
    {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
    <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
       onclick="ezpopmenu_hideAll();
               ezcst_onFoldClicked(this.parentNode);
               return false;"></a>

    {* Label *}
    {set $tooltip = ''}

    {* Text *}
    <span class="node-name-normal">{'Newsletter systems'|i18n( 'newsletter/contentstructuremenu' )}</span>

    {* Show children *}
    {if $have_children}
        {def $last_item = false()
             $children_last_index = $children|count()|dec()}
        <ul>
            {foreach $children as $child_index => $child}
                {if $children_last_index|eq($child_index)}
                    {set $last_item = true()}
                {/if}
                {include name=SubMenu uri="design:parts/newsletter/contentstructuremenu/3_newsletter_system.tpl" newsletter_system_node=$child csm_menu_item_click_action=$csm_menu_item_click_action ui_context=$ui_context last_item=$last_item}
            {/foreach}
        </ul>
    {/if}
</li>
{undef}
