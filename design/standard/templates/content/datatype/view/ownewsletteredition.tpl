<h2>{'Send to mailing list'|i18n('newsletter/datatype/ownewsletteredition')}:</h2>
<ul>
	{def $mailing_list_object = null()}
	{foreach $attribute.content.mailing_list_sending_list as $mailing_list}
		{set $mailing_list_object = fetch( 'content', 'object', hash( 
							'object_id', $mailing_list
						) )}
		<li><a href={$mailing_list_object.main_node.url_alias|ezurl()} target="_blanck">{$mailing_list_object.name}</a></li>
		{/foreach}
</ul>