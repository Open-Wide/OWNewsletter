<div class="block float-break">
	<div class="element">
		<label>{'Send to mailing lists'|i18n('newsletter/datatype/ownewsletteredition')}:</label>
		{if $attribute.content.mailing_lists_ids}
			<ul>
				{def $mailing_list_object = null()}
				{foreach $attribute.content.mailing_lists_ids as $mailing_list}
					{set $mailing_list_object = fetch( 'content', 'object', hash( 
							'object_id', $mailing_list
						) )}
					<li><a href={$mailing_list_object.main_node.url_alias|ezurl()} target="_blanck">{$mailing_list_object.name}</a></li>
					{/foreach}
			</ul>
		{else}
			{'No mailing list'|i18n('newsletter/datatype/ownewsletteredition')}
		{/if}
	</div>
</div>