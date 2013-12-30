<div class="block">
	<h3>{'Sending history'|i18n('design/admin/node/view/full')}</h3>
	<ul>
		<li>
			<label>{'Created'|i18n('design/admin/node/view/full')}</label>
			{if $newsletter_edition.sending.created}
				{$newsletter_edition.sending.created|l10n( shortdatetime )}
			{else}
				{'n/a'|i18n('design/admin/node/view/full')}
			{/if}
		</li>
		<li>
			<label>{'Mail queue creation'|i18n('design/admin/node/view/full')}</label>
			{if $newsletter_edition.sending.mailqueue_created}
				{$newsletter_edition.sending.mailqueue_created|l10n( shortdatetime )}
			{else}
				{'n/a'|i18n('design/admin/node/view/full')}
			{/if}
		</li>
		<li>
			<label>{'Mail queue process start'|i18n('design/admin/node/view/full')}</label>
			{if $newsletter_edition.sending.mailqueue_process_started}
				{$newsletter_edition.sending.mailqueue_process_started|l10n( shortdatetime )}
			{else}
				{'n/a'|i18n('design/admin/node/view/full')}
			{/if}
		</li>
		<li>
			<label>{'Mail queue process finish'|i18n('design/admin/node/view/full')}</label>
			{if $newsletter_edition.sending.mailqueue_process_finished}
				{$newsletter_edition.sending.mailqueue_process_finished|l10n( shortdatetime )}
			{else}
				{'n/a'|i18n('design/admin/node/view/full')}
			{/if}
		</li>
		<li>
			<label>{'Abort'|i18n('design/admin/node/view/full')}</label>
			{if $newsletter_edition.sending.mailqueue_process_aborted}
				{$newsletter_edition.sending.mailqueue_process_aborted|l10n( shortdatetime )}
			{else}
				{'n/a'|i18n('design/admin/node/view/full')}
			{/if}
		</li>
	</ul>
</div>