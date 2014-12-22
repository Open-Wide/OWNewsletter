<div id="content-tree">

    <div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
                            <h4>{'Newsletter systems'|i18n( 'newsletter/menu' )}</h4>
                        </div></div></div></div></div></div>
    <div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
                            <div id="contentstructure">
                                {if ezpreference( 'admin_treemenu' )}
                                    {include uri='design:parts/newsletter/contentstructuremenu/1_newsletter_structure_menu.tpl'}
                                {/if}
                            </div>
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
                        </div></div></div></div></div></div>
</div>
