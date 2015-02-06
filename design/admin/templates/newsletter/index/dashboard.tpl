<div class="block">

    <table cellspacing="0" cellpadding="0" class="list">
        <tr>
            <th width="61%" class="name" colspan="2">{$newsletter_system_node.name|wash()}</th>
        </tr>
        {def $newsletter_node_list = fetch('content', 'list', hash( 'parent_node_id', 
					$newsletter_system_node.node_id,
					'class_filter_type', 'include',
					'class_filter_array', array( 'newsletter' ),
					'sort_by', array( 'name', true() ),	
				) )
			 $newsletter_edition_class_list = fetch( 'newsletter', 'edition_class_list' )}
        {if $newsletter_node_list}
            {foreach $newsletter_node_list as $newsletter_node sequence array( 'bglight', 'bgdark' ) as $style}
                <tr class="{$style}">
                    <td width="61%" align="left">{'newsletter'|class_icon( 'small' )} <a href={$newsletter_node.url_alias|ezurl}>{$newsletter_node.name|wash()}</a></td>
                    <td align="left" width="39%" nowrap>
                        {if and( $newsletter_node.can_create, $newsletter_edition_class_list)}
                            {'Create edition here'|i18n( 'newsletter/index' )} :
                            {foreach $newsletter_edition_class_list as $newsletter_edition_class}
                                <form action={'content/action'|ezurl()} name="CreateNewNewsletterEdition" method="post">
                                    <input type="hidden" value="{ezini( 'RegionalSettings', 'ContentObjectLocale' )}" name="ContentLanguageCode"/>
                                    <input type="hidden" value="{$newsletter_node.node_id}" name="ContentNodeID"/>
                                    <input type="hidden" value="{$newsletter_node.node_id}" name="NodeID"/>
                                    <input type="hidden" value="{$newsletter_edition_class.identifier}" name="ClassIdentifier"/>
                                    <input class="button" type="submit" name="NewButton" value="{$newsletter_edition_class.name}" />
                                </form>
                            {/foreach}
                        {/if}
                    </td>
                </tr>
                {def $edition_draft_node_list = fetch('content','list', hash(
						'parent_node_id', $newsletter_node.node_id,
						'extended_attribute_filter', hash( 
						'id', 'newsletter_edition_filter',
						'params', hash( 'status', 'draft' ) )
					) )}
                {if $edition_draft_node_list|count|gt(0)}
                    {foreach $edition_draft_node_list as $edition_draft_node}
                        <tr class="{$style}">
                            <td width="61%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} title="{'Draft'|i18n('newsletter/edition/status')}" /> <a href={$edition_draft_node.url_alias|ezurl}>{$edition_draft_node.name|wash()}</a></td>
                            <td width="39%">
                                {if $edition_draft_node.can_edit}
                                    <form action={'content/action'|ezurl()} method="post">
                                        <input type="hidden" value="{$edition_draft_node.node_id}" name="TopLevelNodeID"/>
                                        <input type="hidden" value="{$edition_draft_node.node_id}" name="ContentNodeID"/>
                                        <input type="hidden" value="{$edition_draft_node.contentobject_id}" name="ContentObjectID" />
                                        <input type="hidden" value="{'newsletter/index'}" name="RedirectIfDiscarded" />
                                        <input type="hidden" name="ContentObjectLanguageCode" value="{$edition_draft_node.object.current_language}" />
                                        <input class="button" type="submit" title="{'Edit newsletter'|i18n( 'newsletter/index' )}" value="{'Edit'|i18n( 'newsletter/index' )}" name="EditButton" />
                                    </form>
                                {/if}
                        </tr>
                    {/foreach}
                {/if}
                {undef $edition_draft_node_list}
            {/foreach}
        {else}
            <td width="61%" align="left">{'No newsletter'|i18n( 'newsletter/index' )}</td>
            <td align="left" width="39%" nowrap>
                <form action={'content/action'|ezurl()} name="CreateNewNewsletter" method="post">
                    <input type="hidden" value="{ezini( 'RegionalSettings', 'ContentObjectLocale' )}" name="ContentLanguageCode"/>
                    <input type="hidden" value="{$newsletter_system_node.node_id}" name="ContentNodeID"/>
                    <input type="hidden" value="{$newsletter_system_node.node_id}" name="NodeID"/>
                    <input type="hidden" value="newsletter" name="ClassIdentifier"/>
                    <input class="button" type="submit" name="NewButton" value="{'Create newsletter here'|i18n( 'newsletter/index' )}" />
                </form>
            </td>
        {/if}
    </table>
</div>