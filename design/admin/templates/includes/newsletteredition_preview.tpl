{if is_set( $show_iframes )|not() }
    {def $show_iframes = true()}
{/if}

{if is_set( $iframe_height )|not() }
    {def $iframe_height = 200}
{/if}

{def $list_attribute_content = $newsletter_edition_attribute_content.list_attribute_content
     $output_format_array = $list_attribute_content.output_format_array
     $skin_name = $list_attribute_content.skin_name
     $list_main_siteaccess =  $list_attribute_content.main_siteaccess
     $contentobject_id = $newsletter_edition_attribute_content.contentobject_id
     $contentobject_version = $newsletter_edition_attribute_content.contentobject_attribute_version
}

{foreach $output_format_array as $output_format_id => $output_format_name}
    {def $src_url = concat('/newsletter/preview/' , $contentobject_id, '/', $contentobject_version, '/', $output_format_id, '/', $list_main_siteaccess, '/',$skin_name,'/')}
    <img src={concat('newsletter/icons/crystal-newsletter/16x16/preview_', $output_format_id, '.png')|ezimage} title="{$output_format_name}" /> {'Preview'|i18n('newsletter/newsletteredition_preview')} "{$output_format_name}"
    [{'Skin'|i18n('newsletter/newsletteredition_preview')}: {$skin_name}] <a href={$src_url|ezurl} target="new_{$output_format_id}">[{'Fullscreen'|i18n('newsletter/newsletteredition_preview')}]</a>
    {if $show_iframes}
        <iframe src={$src_url|ezurl} width="100%" height="{$iframe_height}" name="EDITION_PREVIEW_{$output_format_id}">
        <p>your browser does not support iframes!</p>
        </iframe>
    {/if}
    {undef $src_url}
{/foreach}