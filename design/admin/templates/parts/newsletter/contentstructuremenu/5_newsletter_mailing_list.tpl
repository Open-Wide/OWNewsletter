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
    {set isRootNode=false()}
{/if}

<li id="nt{$newsletter_mailing_list_node_id}" {if $:last_item} class="lastli"{/if}>

    {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
    <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('newsletter/contentstructuremenu')}"
       onclick="ezpopmenu_hideAll();
               ezcst_onFoldClicked(this.parentNode);
               return false;"></a>

    {* Label *}
    {set toolTip = ''}


    {* icon *}
    {'newsletter_mailing_list'|class_icon( small )}

    {* Text *}

    {* subscription_list *}
    {def $subcriptions_count = 0}
    {set $subcriptions_count = fetch( 'newsletter', 'subscription_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
                                'user_status', 'confirmed'
							) )}
    {if or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
        <a class="nodetext" href={$newsletter_mailing_list_node.url_alias|ezurl} title="{$:toolTip}"><span class="node-name-normal">{$:newsletter_mailing_list_node.name|wash} ({$subcriptions_count})</span></a>
    {else}
        <span class="node-name-normal">{$:newsletter_mailing_list_node.name|wash} ({$subcriptions_count})</span>
    {/if}

    {* Show children *}

    <ul>

        {set $subcriptions_count = fetch( 'newsletter', 'subscription_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'filter_status', 'pending',
                                'user_status', 'confirmed'
							) )}
        <li id="n{$newsletter_mailing_list_node_id}_pending_subscription_list">
            <span class="openclose"></span>
            <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_pending" />
            <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/pending')|ezurl}>
                <span class="node-name-normal">{'Pending'|i18n('newsletter/subscription/status')} ({$subcriptions_count})</span>
            </a>
        </li>

        {set $subcriptions_count = fetch( 'newsletter', 'subscription_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'filter_status', 'approved',
                                'user_status', 'confirmed'
							) )}
        <li id="n{$newsletter_mailing_list_node_id}_approved_subscription_list">
            <span class="openclose"></span>
            <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_approved" />
            <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/approved')|ezurl}>
                <span class="node-name-normal">{'Approved'|i18n('newsletter/subscription/status')} ({$subcriptions_count})</span>
            </a>
        </li>
        {set $subcriptions_count = fetch( 'newsletter', 'subscription_count', hash(
								'mailing_list_contentobject_id', $newsletter_mailing_list_node.contentobject_id,
								'filter_status', 'inactived',
                                'user_status', 'confirmed'
							) )}
        <li id="n{$newsletter_mailing_list_node_id}_inactived_subscription_list" class="lastli">
            <span class="openclose"></span>
            <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_inactived" />
            <a class="nodetext" href={concat($newsletter_mailing_list_node.url_alias,'/(status)/inactived')|ezurl}>
                <span class="node-name-normal">{'Inactived'|i18n('newsletter/subscription/status')} ({$subcriptions_count})</span>
            </a>
        </li>
        {undef}
    </ul>

</li>
{/default}
{/let}
