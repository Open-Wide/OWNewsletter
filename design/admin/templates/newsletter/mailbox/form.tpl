<div class="newsletter newsletter-mailbox_form">
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Mailboxes'|i18n( 'newsletter/mailbox' )}</h2>
                                <div class="header-subline">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-ml">
        <div class="box-mr">
            <div class="box-content">
                <form action={concat( 'newsletter/mailbox/', $mailbox.id )|ezurl()} method="post">
                    <div class="content-navigation-childlist overflow-table">
                        <input type="hidden" name="RedirectUrlActionCancel" value="{$redirect_url_action_cancel}" />
                        <input type="hidden" name="RedirectUrlActionSuccess" value="{$redirect_url_action_success}" />
                        <table class="list" cellspacing="0">
                            <tr>
                                <th>
                                    {'E-mail'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="text" name="Mailbox[email]" id="Mailbox_Email" value="{$mailbox.email}" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {'Server'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="text" name="Mailbox[server]" id="Mailbox_Server" value="{$mailbox.server}" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {'Port'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="text" name="Mailbox[port]" id="Mailbox_Port" value="{$mailbox.port}" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {'User'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="text" name="Mailbox[username]" id="Mailbox_Username" value="{$mailbox.username}" autocomplete="off" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {'Password'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="password" name="Mailbox[password]" id="Mailbox_Password" value="{$mailbox.password}" autocomplete="off" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {'Type'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="radio" name="Mailbox[type]" id="Mailbox_Type_imap" value="imap" {if or( eq( $mailbox.type, 'imap' ), eq( $mailbox.type, 0 ) )}checked{/if}> IMAP
                                    <input type="radio" name="Mailbox[type]" id="Mailbox_Type_pop3" value="pop3" {if eq( $mailbox.type, 'pop3' )}checked{/if}> POP3
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {'SSL'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="checkbox" name="Mailbox[is_ssl]" id="Mailbox_IsSSL" value="{true()}" {if $mailbox.is_ssl}checked="checked"{/if} />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {'Delete e-mails from server'|i18n( 'newsletter/mailbox' )}
                                </th>
                                <td>
                                    <input type="checkbox" name="Mailbox[delete_mails_from_server]" id="Mailbox_DeleteMailsFromServer" value="{true()}" {if $mailbox.delete_mails_from_server}checked="checked"{/if} />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="controlbar">
                        <div class="box-bc">
                            <div class="box-ml">
                                <div class="box-mr">
                                    <div class="box-tc">
                                        <div class="box-bl">
                                            <div class="box-br">
                                                <div class="left">
                                                    <input class="button" type="submit" name="SubmitMailboxButton" value="{'Store and exit'|i18n( 'newsletter/mailbox' )}" />
                                                    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'newsletter/mailbox' )}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{undef}