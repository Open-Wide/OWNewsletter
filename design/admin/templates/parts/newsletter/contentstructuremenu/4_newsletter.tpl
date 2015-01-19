{def $newsletter_node_id = $:newsletter_node.node_id}

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

<li id="nt{$newsletter_node_id}" {if $:last_item} class="lastli"{/if}>

    {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
    <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
       onclick="ezpopmenu_hideAll();
               ezcst_onFoldClicked(this.parentNode);
               return false;"></a>

    {* Label *}
    {set toolTip = ''}


    {* icon *}
    {'newsletter'|class_icon( small )}

    {* Text *}
    {def $newsletter_edition_count = fetch('content','list_count', hash(
					'parent_node_id', $newsletter_node_id ) )}
    {if or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
        <a class="nodetext" href={$:newsletter_node.url_alias|ezurl} title="{$:toolTip}"><span class="node-name-normal">{$:newsletter_node.name|wash} ({$newsletter_edition_count})</span></a>
    {else}
        <span class="node-name-normal">{$:newsletter_node.name|wash} ({$newsletter_edition_count})</span>
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
            <a class="nodetext" href={concat($:newsletter_node.url_alias, '/(status)/draft')|ezurl}>
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
            <a class="nodetext" href={concat($:newsletter_node.url_alias, '/(status)/process')|ezurl}>
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
            <a class="nodetext" href={concat($:newsletter_node.url_alias, '/(status)/archive')|ezurl}>
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
        <li class="lastli" id="n{$newsletter_node_id}_abort">
            <span class="openclose"></span>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} />
            <a class="nodetext" href={concat($:newsletter_node.url_alias, '/(status)/abort')|ezurl}>
                <span class="node-name-normal">{'Aborted'|i18n('newsletter/edition/status')} ({$newsletter_edition_count})</span>
            </a>
        </li>

        {undef}
    </ul>

</li>
{/default}
{/let}
