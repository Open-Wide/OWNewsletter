{* Additional tab windows *}
{foreach $additional_tabs as $tab}
{def $tab_template = ezini( concat( 'AdditionalTab_', $tab ), 'Template', 'admininterface.ini' )}

<div id="node-tab-{$tab}-content" class="tab-content{if $node_tab_index|ne( $tab )} hide{else} selected{/if}">
    {include uri=concat( 'design:tabs/', $tab_template )}
<div class="break"></div>
</div>

{undef $tab_template}
{/foreach}
<div id="node-tab-newsletter-preview-content" class="tab-content{if $node_tab_index|ne( $tab )} hide{else} selected{/if}">
    {include uri='design:tabs/newsletter_edition/preview.tpl'}
<div class="break"></div>
</div>
<div id="node-tab-newsletter-statistics-content" class="tab-content{if $node_tab_index|ne( $tab )} hide{else} selected{/if}">
    {include uri='design:tabs/newsletter_edition/statistics.tpl'}
<div class="break"></div>
</div>