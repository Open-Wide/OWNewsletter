{def $counter = 0}
{foreach $additional_tabs as $tab}
    {def $tab_title = ezini( concat( 'AdditionalTab_', $tab ), 'Title', 'admininterface.ini' )|wash()
         $tab_description = ezini( concat( 'AdditionalTab_', $tab ), 'Description', 'admininterface.ini' )|wash()
         $tab_header_template = ezini( concat( 'AdditionalTab_', $tab ), 'HeaderTemplate', 'admininterface.ini' )
         $last = false()}
    {set $counter = $counter|inc()}

    {if eq( $tab_header_template, '' )}
        <li id="node-tab-{$tab}" class="{if $last}last{else}middle{/if}{if $node_tab_index|eq( $tab )} selected{/if}">
            {if $tabs_disabled}
                <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{$tab_title}</span>
            {else}
                <a href={concat( $node_url_alias, '/(tab)/', $tab )|ezurl} title="{$tab_description}">{$tab_title}</a>
            {/if}
        </li>
    {else}
        {include uri=concat( 'design:tabs/', $tab_header_template ) last=$last}
    {/if}

    {undef $tab_title $tab_description $tab_header_template $last}
{/foreach}
{include uri='design:tabs/newsletter_edition/preview_header.tpl' last=false()}
{include uri='design:tabs/newsletter_edition/statistics_header.tpl' last=true()}
{undef $counter}
