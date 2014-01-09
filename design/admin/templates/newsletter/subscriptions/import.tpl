{def $base_uri = concat( 'newsletter/subscription_import/', $mailing_list.id )}
<div class="newsletter newsletter-subscription_import">
	<form enctype="multipart/form-data" name="SubscriptionImport" method="post" action={$base_uri|ezurl}>
		<div class="context-block">
			<div class="box-header">
				<div class="box-tc">
					<div class="box-ml">
						<div class="box-mr">
							<div class="box-tl">
								<div class="box-tr">
									<h1 class="context-title">{"Import subscriptions"|i18n( 'newsletter/subscription/import' )}</h1>
									<div class="header-mainline">
									</div>
									{if is_set($warning)}
										<div class="message-warning">
											<h2>{$warning|wash}</h2>
										</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box-ml">
				<div class="box-mr">
					<div class="box-content">
						<div class="context-attributes">
							<div class="block float-break">
							</div
							<div class="block">
								<label>
									{'Column delimiter'|i18n( 'newsletter/subscription/import' )}:
									<select name="ColumnDelimiter">
										<option value=";" {if $column_delimiter|eq(';')}selected="selected"{/if}>;</option>
										<option value="," {if $column_delimiter|eq(',')}selected="selected"{/if}>,</option>
										<option value="|" {if $column_delimiter|eq('|')}selected="selected"{/if}>|</option>
									</select>
								</label>
							</div>
							<div class="block">
								<label>
									{'Upload file'|i18n( 'newsletter/subscription/import' )}: 
									<input type="hidden" name="MAX_FILE_SIZE" value="0" />
									<input name="UploadFile" type="file" />
								</label>
							</div>
							<div class="break">
							</div>
						</div>
					</div>
					<div class="controlbar">
						<div class="box-bc">
							<div class="box-ml">
								<div class="box-mr">
									<div class="box-tc">
										<div class="box-bl">
											<div class="box-br">
												<div class="left">
													<input type="hidden" name="RedirectUrlActionCancel" value="{$redirect_url_action_cancel}" />
													<input type="hidden" name="RedirectUrlActionSuccess" value="{$redirect_url_action_success}" />
													<input class="button" type="submit" name="ImportButton" value="{'Import'|i18n( 'newsletter/subscription/import' )}" title="{'Import subscriptions.'|i18n( 'newsletter/subscription/import' )}" />
													<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'newsletter/subscription/import' )}" title="{'Cancel subscription import.'|i18n( 'newsletter/subscription/import' )}" />
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
	</form>
</div>
