{def $sending = $newsletter_edition.sending}
<div class="block">
    <h3>{'Sending statistics'|i18n('design/admin/node/view/full')}</h3>
    <table class="list" cellspacing="0">
        <tr>
            <th>{'E-mails count'|i18n( 'design/admin/node/view/full' )}</th>
            <th>{'E-mails sent'|i18n( 'design/admin/node/view/full' )}</th>
            <th>{'E-mails not sent'|i18n( 'design/admin/node/view/full' )}</th>
            <th>{'E-mails bounced'|i18n( 'design/admin/node/view/full' )}</th>
        </tr>
        <tr>
            <td>{$sending.statistics.items_count}</td>
            <td>{$sending.statistics.items_send}</td>
            <td>{$sending.statistics.items_not_send}</td>
            <td>{$sending.statistics.items_bounced}</td>
        </tr>
    </table>

    <h3>{'Sending history'|i18n('design/admin/node/view/full')}</h3>
    <ul>
        <li>
            <label>{'Created'|i18n('design/admin/node/view/full')}</label>
            {if $sending.created}
                {$sending.created|l10n( shortdatetime )}
            {else}
                {'n/a'|i18n('design/admin/node/view/full')}
            {/if}
        </li>
        <li>
            <label>{'Waiting for process'|i18n('design/admin/node/view/full')}</label>
            {if $sending.waiting_for_process}
                {$sending.waiting_for_process|l10n( shortdatetime )}
            {else}
                {'n/a'|i18n('design/admin/node/view/full')}
            {/if}
        </li>
        <li>
            <label>{'E-mail queue creation'|i18n('design/admin/node/view/full')}</label>
            {if $sending.mailqueue_created}
                {$sending.mailqueue_created|l10n( shortdatetime )}
            {else}
                {'n/a'|i18n('design/admin/node/view/full')}
            {/if}
        </li>
        <li>
            <label>{'E-mail queue process start'|i18n('design/admin/node/view/full')}</label>
            {if $sending.mailqueue_process_started}
                {$sending.mailqueue_process_started|l10n( shortdatetime )}
            {else}
                {'n/a'|i18n('design/admin/node/view/full')}
            {/if}
        </li>
        <li>
            <label>{'E-mail queue process finish'|i18n('design/admin/node/view/full')}</label>
            {if $sending.mailqueue_process_finished}
                {$sending.mailqueue_process_finished|l10n( shortdatetime )}
            {else}
                {'n/a'|i18n('design/admin/node/view/full')}
            {/if}
        </li>
        <li>
            <label>{'Abort'|i18n('design/admin/node/view/full')}</label>
            {if $sending.mailqueue_process_aborted}
                {$sending.mailqueue_process_aborted|l10n( shortdatetime )}
            {else}
                {'n/a'|i18n('design/admin/node/view/full')}
            {/if}
        </li>
    </ul>
</div>