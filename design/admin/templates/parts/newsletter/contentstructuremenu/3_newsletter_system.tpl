{def $newsletter_root_node_id = $newsletter_system_node.node_id
	 $mailing_list_children       = fetch( 'content', 'list', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array', array( 'newsletter_mailing_list_collection' ),
                'sort_by', $newsletter_system_node.sort_array
			) )
	 $newsletter_children       = fetch( 'content', 'list', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array', array( 'newsletter' ) ,
                'sort_by', $newsletter_system_node.sort_array
			) )
	 $num_children    = fetch( 'content', 'list_count', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array',
				array('newsletter_mailing_list_collection', 'newsletter') 
			) )
	 $have_children   = $num_children|gt(0)
	 $show_tooltips   = ezini( 'TreeMenu', 'ToolTips' , 'contentstructuremenu.ini' )
	 $translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
	 $tooltip        = ""
	 $visibility     = 'Visible'
	 $is_root_node     = false()}

{if is_set($class_icons_size)}
    {set $classIconsSize=$class_icons_size}
{/if}

{if is_set($is_root_node)}
    {set $is_root_node=$is_root_node}
{/if}

<li id="n0_{$newsletter_system_node.node_id}" {cond( $last_item, 'class="lastli"', '' )}>

    {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
    <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
       onclick="ezpopmenu_hideAll();
               ezcst_onFoldClicked(this.parentNode);
               return false;"></a>

    {* Label *}
    {set $tooltip = ''}

    {* Text *}

    {* icon *}
    {*<img src={'/share/icons/crystal-admin/16x16_indexed/filesystems/folder_txt.png'|ezroot}>*}
    {'newsletter_system'|class_icon( small )}

    {if or( eq($ui_context, 'browse')|not(), eq($parentNode.object.is_container, true()))}
        <a class="nodetext" href={$newsletter_system_node.url_alias|ezurl} title="{$tooltip}"><span class="node-name-normal">{$newsletter_system_node.name}</span></a>
        {else}
        <span class="node-name-normal">{$newsletter_system_node.name|wash}</span>
    {/if}

    {* Show children *}
    {if $have_children}
        {set $last_item = false()}
        {def $mailing_list_children_last_index = $mailing_list_children|count()|dec()
             $newsletter_children_last_index = $newsletter_children|count()|dec()}
        <ul>
            {foreach $mailing_list_children as $child_index => $child}
                {if and( $newsletter_children|count()|eq(0), $mailing_list_children_last_index|eq($child_index) )}
                    {set $last_item = true()}
                {/if}
                {include name=SubMenu uri="design:parts/newsletter/contentstructuremenu/4_newsletter_mailing_list_collection.tpl" newsletter_mailing_list_collection_node=$child csm_menu_item_click_action=$csm_menu_item_click_action last_item=$last_item ui_context=$ui_context}
            {/foreach}
            {foreach $newsletter_children as $child_index => $child}
                {if $newsletter_children_last_index|eq($child_index)}
                    {set $last_item = true()}
                {/if}
                {include name=SubMenu uri="design:parts/newsletter/contentstructuremenu/4_newsletter.tpl" newsletter_node=$child csm_menu_item_click_action=$csm_menu_item_click_action ui_context=$ui_context last_item=$last_item}
            {/foreach}
        </ul>
    {/if}
</li>
{undef}
