{if is_set( $show_actions_colum )|not}
    {def $show_actions_colum = false()}
{/if}

{if is_set( $can_copy )|not}
    {def $can_copy = true()}
{/if}
<div class="content-navigation-childlist overflow-table">
    <table class="list" cellspacing="0">
        <tr>
            {* Name column *}
            <th class="name">{'Name'|i18n( 'newsletter/index' )}</th>
            <th>{'E-mails'|i18n( 'newsletter/index' )}</th>
            <th>{'Bounced'|i18n( 'newsletter/index' )}</th>
            <th class="modified">{'Modified'|i18n( 'newsletter/index' )}</th>

            {if $show_actions_colum}
				{* Copy column *}
				<th class="copy">&nbsp;</th>
					{* Edit column *}
				<th class="edit">&nbsp;</th>
				{/if}
        </tr>

        {foreach $edition_node_list as $edition_node sequence array( 'bglight', 'bgdark' ) as $style}
			{def $child_name = $edition_node.name|wash
				$node_content = $edition_node.object
				$newsletter_edition = $edition_node|newsletter_edition_content()}

			<tr class="{$style}">
				{* Name *}
				<td>
					{*node_view_gui view=line content_node=$edition_node*}
					<img class="transparent-png-icon" src={concat('images/newsletter/icons/crystal-newsletter/16x16/newsletter_', $newsletter_edition.status, '.png')|ezdesign()} width="16" height="16" alt="{$newsletter_edition.status_name|wash()}" title="{$newsletter_edition.status_name|wash()}" />
					<a href={$edition_node.url_alias|ezurl()}>{$edition_node.name|wash()}</a>
				</td>
				<td nowrap>
					{if $newsletter_edition.status|ne('draft')}
						{def $current_send_statistic = $newsletter_edition.sending.send_items_statistic}
						{$current_send_statistic.items_send|wash()}/{$current_send_statistic.items_count|wash()} ({$current_send_statistic.items_send_in_percent|wash()}%)
						{undef $current_send_statistic}
					{/if}
				</td>
				<td nowrap>
					{if $newsletter_edition.status|ne('draft')}
						{$current_send_statistic.sending.items_bounced|wash()}
					{/if}
				</td>

				{* Published *}
				<td class="published" nowrap>{$edition_node.object.modified|l10n( 'shortdatetime' )}</td>

				{if $show_actions_colum}
					{* Copy button *}
					<td>
						{if $can_copy}
							<a href={concat( 'content/copysubtree/', $edition_node.node_id )|ezurl()}><img src={'copy.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="{'Create a copy of <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash()}" /></a>
							{else}
							<img src={'copy-disabled.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="" />
						{/if}
					</td>
					{* Edit button *}
					<td>
						{if and( $edition_node.can_edit, $newsletter_edition.is_draft )}
							<form action={'content/action'|ezurl()} method="post">
								<input type="hidden" value="{$edition_node.node_id}" name="TopLevelNodeID"/>
								<input type="hidden" value="{$edition_node.node_id}" name="ContentNodeID"/>
								<input type="hidden" value="{$edition_node.contentobject_id}" name="ContentObjectID" />
								{* <input type="hidden" value="{'newsletter/index'}" name="RedirectIfDiscarded" />*}
								<input type="hidden" name="ContentObjectLanguageCode" value="{$edition_node.object.current_language}">
								<input name="EditButton" type="image" src={'edit.gif'|ezimage} alt="{'Edit newsletter'|i18n( 'newsletter/index' )}" title="{'Edit newsletter'|i18n( 'newsletter/index' )}" />
							</form>

							{* <a href={concat( 'content/edit/', $edition_node.contentobject_id )|ezurl()}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash()}" /></a>*}
						{else}
							<img src={'edit-disabled.gif'|ezimage} alt="{'Edit newsletter'|i18n( 'newsletter/index' )}" title="" />
						{/if}
					</td>
				{/if}
			</tr>

			{undef $child_name $node_content $newsletter_edition $newsletter_edition.status $newsletter_edition.is_draft}
        {/foreach}
    </table>
</div>

