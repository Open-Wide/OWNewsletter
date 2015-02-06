{* Newsletter related stuff *}
<div id="newsletter-menu">
    {if $module_result.ui_context|ne('edit')}
        {include uri='design:parts/newsletter/newsletter_menu.tpl'}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h4>{'Administer'|i18n( 'newsletter/menu' )}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-bc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-bl">
                        <div class="box-br">
                            <div class="box-content">
                                <ul>
                                    {if fetch( 'user', 'has_access_to', hash( 'module', 'newsletter', 'function', 'user' ) ) }
                                        <li>
                                            <a href={'/newsletter/user'|ezurl}>{'Users'|i18n( 'newsletter/menu' )} ({fetch( 'newsletter', 'user_count' )})</a>
                                            <ul>
                                                <li id="pending_user_list">
                                                    <span class="openclose"></span>
                                                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_pending" />
                                                    <a class="nodetext" href={'/newsletter/user/(status)/pending'|ezurl}>
                                                        <span class="node-name-normal">{'Pending'|i18n('newsletter/user/status')} ({fetch( 'newsletter', 'user_count', hash(
                                                            'user_status', 'pending'
                                                        ) )})</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li id="confirmed_user_list">
                                                    <span class="openclose"></span>
                                                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_confirmed" />
                                                    <a class="nodetext" href={'/newsletter/user/(status)/confirmed'|ezurl}>
                                                        <span class="node-name-normal">{'Confirmed'|i18n('newsletter/user/status')} ({fetch( 'newsletter', 'user_count', hash(
                                                            'user_status', 'confirmed'
                                                        ) )})</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li id="bounced_user_list">
                                                    <span class="openclose"></span>
                                                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_bounced" />
                                                    <a class="nodetext" href={'/newsletter/user/(status)/bounced'|ezurl}>
                                                        <span class="node-name-normal">{'Bounced'|i18n('newsletter/user/status')} ({fetch( 'newsletter', 'user_count', hash(
                                                            'user_status', 'bounced'
                                                        ) )})</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li id="removed_user_list">
                                                    <span class="openclose"></span>
                                                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_removed" />
                                                    <a class="nodetext" href={'/newsletter/user/(status)/removed'|ezurl}>
                                                        <span class="node-name-normal">{'Removed'|i18n('newsletter/user/status')} ({fetch( 'newsletter', 'user_count', hash(
                                                            'user_status', 'removed'
                                                        ) )})</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li id="blacklisted_user_list">
                                                    <span class="openclose"></span>
                                                    <img src={'1x1.gif'|ezimage} alt="" title="" class="icon12 icon_s_blacklisted" />
                                                    <a class="nodetext" href={'/newsletter/user/(status)/blacklisted'|ezurl}>
                                                        <span class="node-name-normal">{'Blacklisted'|i18n('newsletter/user/status')} ({fetch( 'newsletter', 'user_count', hash(
                                                            'user_status', 'blacklisted'
                                                        ) )})</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    {else}
                                        <li class="disabled-no-access">
                                            <div><span class="disabled">{'Users'|i18n( 'newsletter/menu' )}</span></div>
                                        </li>
                                    {/if}
                                    {if fetch( 'user', 'has_access_to', hash( 'module', 'newsletter', 'function', 'admin' ) ) }
                                        <li>
                                            <a href={'/newsletter/blacklist'|ezurl}>{'Blacklists'|i18n( 'newsletter/menu' )}</a>
                                        </li>
                                    {else}
                                        <li class="disabled-no-access">
                                            <div><span class="disabled">{'Blacklists'|i18n( 'newsletter/menu' )}</span></div>
                                        </li>
                                    {/if}
                                    {if fetch( 'user', 'has_access_to', hash( 'module', 'newsletter', 'function', 'mailbox' ) ) }
                                        <li>
                                            <a href={'/newsletter/bounce'|ezurl}>{'Bounces'|i18n( 'newsletter/menu' )}</a>
                                        </li>
                                    {else}
                                        <li class="disabled-no-access">
                                            <div><span class="disabled">{'Bounces'|i18n( 'newsletter/menu' )}</span></div>
                                        </li>
                                    {/if}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {include uri='design:parts/newsletter/newsletter_menu_admin.tpl'}
</div>

{* This is the border placed to the left for draging width, js will handle disabling the one above and enabling this *}
<div id="widthcontrol-handler" class="hide">
    <div class="widthcontrol-grippy"></div>
    <div id="widthcontrol-links" class="widthcontrol">
        <p>
            {switch match=ezpreference( 'admin_left_menu_width' )}
            {case match='medium'}
            <a href={'/user/preferences/set/admin_left_menu_width/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'newsletter/newsletter_menu' )}">{'Small'|i18n( 'newsletter/newsletter_menu' )}</a>
            <span class="current">{'Medium'|i18n( 'newsletter/newsletter_menu' )}</span>
            <a href={'/user/preferences/set/admin_left_menu_width/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'newsletter/newsletter_menu' )}">{'Large'|i18n( 'newsletter/newsletter_menu' )}</a>
            {/case}

            {case match='large'}
            <a href={'/user/preferences/set/admin_left_menu_width/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'newsletter/newsletter_menu' )}">{'Small'|i18n( 'newsletter/newsletter_menu' )}</a>
            <a href={'/user/preferences/set/admin_left_menu_width/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'newsletter/newsletter_menu' )}">{'Medium'|i18n( 'newsletter/newsletter_menu' )}</a>
            <span class="current">{'Large'|i18n( 'newsletter/newsletter_menu' )}</span>
            {/case}

            {case}
            <span class="current">{'Small'|i18n( 'newsletter/newsletter_menu' )}</span>
            <a href={'/user/preferences/set/admin_left_menu_width/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'newsletter/newsletter_menu' )}">{'Medium'|i18n( 'newsletter/newsletter_menu' )}</a>
            <a href={'/user/preferences/set/admin_left_menu_width/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'newsletter/newsletter_menu' )}">{'Large'|i18n( 'newsletter/newsletter_menu' )}</a>
            {/case}
            {/switch}
        </p>
    </div>
</div>
{undef}