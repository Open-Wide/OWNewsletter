{let $newsletter_root_node_id = $newsletter_mailing_list_collection_node.node_id
	 $children       = fetch( 'content', 'list', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array',
				array( 'newsletter_mailing_list' ) 
			) )
	 $num_children    = fetch( 'content', 'list_count', hash(
				'parent_node_id', $newsletter_root_node_id,
				'class_filter_type', 'include',
				'class_filter_array',
				array('newsletter_mailing_list') 
			) )
	 $have_children   = $num_children|gt(0)
	 $show_tooltips   = ezini( 'TreeMenu', 'ToolTips' , 'contentstructuremenu.ini' )
	 $translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
	 $tooltips        = ""
	 $visibility     = 'Visible'
	 $is_root_node     = false() }

{if is_set($class_icons_size)}
    {set $class_icons_size=$class_icons_size}
{/if}

{if is_set($is_root_node)}
    {set $is_root_node=$is_root_node}
{/if}

<li id="n0_{$newsletter_mailing_list_collection_node.node_id}" {cond( $last_item, 'class="lastli"', '' )}>

    {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
    <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
       onclick="ezpopmenu_hideAll();
               ezcst_onFoldClicked(this.parentNode);
               return false;"></a>

    {* Label *}
    {set $tooltips = ''}

    {* Text *}

    {* icon *}
    {'newsletter_mailing_list_collection'|class_icon( small )}

    {if or( eq($ui_context, 'browse')|not(), eq($parentNode.object.is_container, true()))}
        <a class="nodetext" href={$newsletter_mailing_list_collection_node.url_alias|ezurl} title="{$tooltips}"><span class="node-name-normal">{$newsletter_mailing_list_collection_node.name}</span></a>
        {else}
        <span class="node-name-normal">{$newsletter_mailing_list_collection_node.name|wash}</span>
    {/if}

    {* Show children *}
    {if $have_children}
        {set $last_item = false()}
        {def $children_last_index = $children|count()|dec()}
        <ul>
            {foreach $children as $child_index => $child}
                {if $children_last_index|eq($child_index)}
                    {set $last_item = true()}
                {/if}
                {include name=SubMenu uri="design:parts/newsletter/contentstructuremenu/5_newsletter_mailing_list.tpl" newsletter_mailing_list_node=$child csm_menu_item_click_action=$csm_menu_item_click_action last_item=$last_item ui_context=$ui_context}
            {/foreach}
        </ul>
    {/if}
</li>
{undef}
