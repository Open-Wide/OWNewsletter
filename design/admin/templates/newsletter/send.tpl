<div class="newsletter newsletter-index">
    <div class="border-box">
        <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
        <div class="border-ml">
            <div class="border-mr">
                <div class="border-mc float-break">
                    <div class="context-block">
                        <div class="box-header">
                            <div class="box-tc">
                                <div class="box-ml">
                                    <div class="box-mr">
                                        <div class="box-tl">
                                            <div class="box-tr">
                                                <h1 class="context-title">{'Newsletter sending'|i18n( 'newsletter/send' )}</h1>
                                                <div class="header-mainline"></div>
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
                                                {switch match=$current_action}

                                                {case match='SendNewsletterSoonAsPossible'}
                                                {if is_set( $errors )}
                                                    <p>{'An error occurred during the sending of the newsletter'|i18n('newsletter/send')}:</p>
                                                    <ul>
                                                        {foreach $errors as $error}
                                                            <li>{$error}</li>
                                                            {/foreach}
                                                    </ul>
                                                {else}
                                                    <p>{'The newsletter will be sent as soon as possible.'|i18n('newsletter/send')}</p>
                                                {/if}
                                                {/case}

                                                {case match='SendNewsletterFromDate'}
                                                {if is_set( $errors )}
                                                    <p>{'An error occurred during the sending of the newsletter'|i18n('newsletter/send')}:</p>
                                                    <ul>
                                                        {foreach $errors as $error}
                                                            <li>{$error}</li>
                                                            {/foreach}
                                                    </ul>
                                                {else}
                                                    <p>{'The newsletter will be sent from %sending_date.'|i18n('newsletter/send', , hash('%sending_date', $newsletter_sending_date|l10n( 'shortdate' ) ) )}</p>
                                                {/if}
                                                {/case}

                                                {case match='AbortNewsletter'} 
                                                {if is_set( $errors )}
                                                    <p>{'An error occurred during the cancellation of the sending of the newsletter'|i18n('newsletter/send')}:</p>
                                                    <ul>
                                                        {foreach $errors as $error}
                                                            <li>{$error}</li>
                                                            {/foreach}
                                                    </ul>
                                                {else}
                                                    <p>{'The sending of the newsletter was cancelled.'|i18n('newsletter/send')}</p>
                                                {/if}
                                                {/case}

                                                {case match='SendNewsletterTest'} 
                                                {if is_set( $errors )}
                                                    <p>{'An error occurred during the sending of the newsletter of test'|i18n('newsletter/send')}:</p>
                                                    <ul>
                                                        {foreach $errors as $error}
                                                            <li>{$error}</li>
                                                            {/foreach}
                                                    </ul>
                                                {else}
                                                    <p>{'The newsletter of test was successfully sent to these addresses: %addresses.'|i18n('newsletter/send', , hash('%addresses', $test_receiver_email) )}</p>
                                                {/if}
                                                {/case}
                                                {/switch}
                                                <p><a href={$node.url_alias|ezurl()} alt="{'Back to newsletter'|i18n('newsletter/send')}">{'Back to newsletter'|i18n('newsletter/send')}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>
</div>

