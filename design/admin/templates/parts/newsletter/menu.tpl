{* Newsletter related stuff *}
<div id="newsletter-menu">
    {if $module_result.ui_context|ne('edit')}
        {include uri='design:parts/newsletter/newsletter_menu.tpl'}
        {def $has_admin_access = fetch( 'user', 'has_access_to', hash(
                    'module', 'newsletter',
                    'function', 'admin' 
            ) ) }
        {if $has_admin_access}
            <div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
                <h4>{'Administer'|i18n( 'newsletter/menu' )}</h4>
            </div></div></div></div></div></div>
            <div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
                <ul>
                    <li><a href={'/newsletter/user/'|ezurl}>{'Users'|i18n( 'newsletter/menu' )}{* User search*}</a></li>
                    <li><a href={'/newsletter/blacklist'|ezurl}>{'Blacklists'|i18n( 'newsletter/menu' )}</a></li>
                    <li><a href={'/newsletter/bounce'|ezurl}>{'Bounces'|i18n( 'newsletter/menu' )}</a></li>
                </ul>
            </div></div></div></div></div></div>
        {/if}
        {include uri='design:parts/newsletter/newsletter_menu_admin.tpl'}
    {/if}
</div>

{* This is the border placed to the left for draging width, js will handle disabling the one above and enabling this *}
<div id="widthcontrol-handler" class="hide">
    <div class="widthcontrol-grippy"></div>
</div>
