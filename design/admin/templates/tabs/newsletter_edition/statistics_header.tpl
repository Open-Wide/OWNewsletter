<li id="node-tab-newsletter-statistics" class="{if $last}last{else}middle{/if}{if $node_tab_index|eq('newsletter-statistics')} selected{/if}">
    {if $tabs_disabled}
        <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'Newsletter statistics'|i18n( 'design/admin/node/view/full' )}</span>
    {else}
        <a href={concat( $node_url_alias, '/(tab)/newsletter-statistics' )|ezurl} title="{'Show newsletter statistics.'|i18n( 'design/admin/node/view/full' )}">{'Newsletter statistics'|i18n( 'design/admin/node/view/full' )}</a>
    {/if}
</li>
