{* admin function - only show if user has policy: supporttools admin*}

{def $adminMenu= fetch( 'user', 'has_access_to', hash( 'module',   'newsletter', 'function', 'admin'  ) ) }      
{if eq($adminMenu,true)}
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
                                <li><a href={'/newsletter/mailbox'|ezurl()}>{'E-mail accounts'|i18n( 'newsletter/menu' )}</a></li>
                                <li><a href={'/newsletter/subscribe/'|ezurl()}>{'Subscription form'|i18n( 'newsletter/menu' )}</a></li>
                                <li><a href={'/newsletter/settings'|ezurl()}>{'INI Settings'|i18n( 'newsletter/menu' )}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}