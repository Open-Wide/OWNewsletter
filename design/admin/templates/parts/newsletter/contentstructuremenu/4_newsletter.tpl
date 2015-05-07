{def $newsletter_node_id = $newsletter_node.node_id}

{def $children       = array()
     $num_children    = array()
     $have_children   = $num_children|gt(0)
     $show_tooltips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
     $translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
     $tooltips        = ""
     $visibility     = 'Visible'
     $is_root_node     = false() }

{if is_set($class_icons_size)}
    {set $class_icons_size=$class_icons_size}
{/if}

{if is_set($is_root_node)}
    {set $is_root_node=false()}
{/if}
<li id="nt{$newsletter_node_id}" {cond( $last_item,  cond(eq($current_uri_string,$newsletter_node.url_alias),'class="lastli activenode"','class="lastli"') ,cond(eq($current_uri_string,$newsletter_node.url_alias),'class="activenode"','') )} >

    {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
    <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
       onclick="ezpopmenu_hideAll();
               ezcst_onFoldClicked(this.parentNode);
               return false;"></a>

    {* Label *}
    {set $tooltips = ''}


    {* icon *}
    {'newsletter'|class_icon( small )}

    {* Text *}
    {def $newsletter_edition_count = fetch('content','list_count', hash(
					'parent_node_id', $newsletter_node_id ) )}
    {if or( eq($ui_context, 'browse')|not(), eq($parentNode.object.is_container, true()))}
        <a class="nodetext" href={$newsletter_node.url_alias|ezurl} title="{$tooltips}"><span class="node-name-normal">{$newsletter_node.name|wash} ({$newsletter_edition_count})</span></a>
    {else}
        <span class="node-name-normal">{$newsletter_node.name|wash} ({$newsletter_edition_count})</span>
    {/if}

    {* Show children *}

    <ul>
        {* draft *}
        {set $newsletter_edition_count = fetch('content','list_count', hash(
					'parent_node_id', $newsletter_node_id,
					'extended_attribute_filter', hash(	
						'id', 'newsletter_edition_filter',
						'params', hash( 'status', 'draft' ) )
				) )}
        <li id="n{$newsletter_node_id}_draft">
            <span class="openclose"></span>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} />
            <a class="nodetext" href={concat($newsletter_node.url_alias, '/(status)/draft')|ezurl}>
                <span class="node-name-normal">{'Draft'|i18n('newsletter/edition/status')} ({$newsletter_edition_count})</span>
            </a>
        </li>
        {* process *}
        {set $newsletter_edition_count = fetch('content','list_count', hash(
					'parent_node_id', $newsletter_node_id,
					'extended_attribute_filter', hash(	
						'id', 'newsletter_edition_filter',
						'params', hash( 'status', 'process' ) )
				) )}
        <li id="n{$newsletter_node_id}_process">
            <span class="openclose"></span>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} />
            <a class="nodetext" href={concat($newsletter_node.url_alias, '/(status)/process')|ezurl}>
                <span class="node-name-normal">{'Sending'|i18n('newsletter/edition/status')} ({$newsletter_edition_count})</span>
            </a>
        </li>
        {* Archive *}
        {set $newsletter_edition_count = fetch('content','list_count', hash(
					'parent_node_id', $newsletter_node_id,
					'extended_attribute_filter', hash(	
						'id', 'newsletter_edition_filter',
						'params', hash( 'status', 'archive' ) )
				) )}
        <li id="n{$newsletter_node_id}_archive">
            <span class="openclose"></span>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} />
            <a class="nodetext" href={concat($newsletter_node.url_alias, '/(status)/archive')|ezurl}>
                <span class="node-name-normal">{'Archived'|i18n('newsletter/edition/status')} ({$newsletter_edition_count})</span>
            </a>
        </li>
        {* Abort *}
        {set $newsletter_edition_count = fetch('content','list_count', hash(
					'parent_node_id', $newsletter_node_id,
					'extended_attribute_filter', hash(	
						'id', 'newsletter_edition_filter',
						'params', hash( 'status', 'abort' ) )
				) )}
        <li class="lastli" id="n{$newsletter_node_id}_abort" class="lastli">
            <span class="openclose"></span>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} />
            <a class="nodetext" href={concat($newsletter_node.url_alias, '/(status)/abort')|ezurl}>
                <span class="node-name-normal">{'Aborted'|i18n('newsletter/edition/status')} ({$newsletter_edition_count})</span>
            </a>
        </li>

        {undef}
    </ul>

</li>
{undef}
