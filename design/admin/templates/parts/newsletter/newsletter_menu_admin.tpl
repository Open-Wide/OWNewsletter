{* admin function - only show if user has policy: supporttools admin*}

{if fetch( 'user', 'has_access_to', hash( 
			'module', 'newsletter',
			'function', 'admin' ) )}
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h4>{'Settings'|i18n( 'newsletter/menu' )}</h4>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-bc">
        <div class="box-ml">
            <div class="box-mr"><div class="box-bl">
                    <div class="box-br">
                        <div class="box-content">
                            <ul>
                                {if fetch( 'user', 'has_access_to', hash( 'module', 'newsletter', 'function', 'mailbox' ) ) }
                                    <li><a href={'/newsletter/mailbox'|ezurl()}>{'E-mail accounts'|i18n( 'newsletter/menu' )}</a></li>
                                {else}
                                    <li class="disabled-no-access"><div><span class="disabled">{'E-mail accounts'|i18n( 'newsletter/menu' )}</span></div></li>
                                {/if}
                                {if fetch( 'user', 'has_access_to', hash( 'module', 'newsletter', 'function', 'subscribe' ) ) }
                                    <li><a href={'/newsletter/subscribe'|ezurl()}>{'Subscription form'|i18n( 'newsletter/menu' )}</a></li>
                                {else}
                                    <li class="disabled-no-access"><div><span class="disabled">{'Subscription form'|i18n( 'newsletter/menu' )}</span></div></li>
                                {/if}
                                {if fetch( 'user', 'has_access_to', hash( 'module', 'newsletter', 'function', 'settings' ) ) }
                                    <li><a href={'/newsletter/settings'|ezurl()}>{'INI Settings'|i18n( 'newsletter/menu' )}</a></li>
                                {else}
                                    <li class="disabled-no-access"><div><span class="disabled">{'INI Settings'|i18n( 'newsletter/menu' )}</span></div></li>
                                {/if}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}