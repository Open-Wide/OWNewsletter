{def $base_uri = concat( 'newsletter/subscription_export/', $mailing_list.id )}
<div class="newsletter newsletter-subscription_export">
	<form enctype="multipart/form-data" name="SubscriptionExport" method="post" action={$base_uri|ezurl}>
		<div class="context-block">
			<div class="box-header">
				<div class="box-tc">
					<div class="box-ml">
						<div class="box-mr">
							<div class="box-tl">
								<div class="box-tr">
									<h1 class="context-title">{"Export subscriptions"|i18n( 'newsletter/subscription/export' )}</h1>
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
									{'Column delimiter'|i18n( 'newsletter/subscription/export' )}:
									<select name="ColumnDelimiter">
										<option value=";" {if $column_delimiter|eq(';')}selected="selected"{/if}>;</option>
										<option value="," {if $column_delimiter|eq(',')}selected="selected"{/if}>,</option>
										<option value="|" {if $column_delimiter|eq('|')}selected="selected"{/if}>|</option>
									</select>
								</label>
							</div>
							<div class="block">
								<label>
									{def $available_subscription_status_list = fetch( 'newsletter', 'available_subscription_status' )}
									{'Subscription status'|i18n( 'newsletter/subscription/export' )}: 
									<select multiple="multiple" name="SubscriptionStatus[]">
										{foreach $available_subscription_status_list as $status_id => $status_name}
											<option value="{$status_id}" {if $selected_status_list|contains($status_id)}selected="selected"{/if}>{$status_name}</option>
										{/foreach}
									</select>
								</label>
							</div>
							<div class="block">
								<label>
									{'Export fields'|i18n( 'newsletter/subscription/export' )}: 
									<select multiple="multiple" name="SubscriptionFields[]">
										{foreach $available_field_list as $field}
											<option value="{$field}" {if $selected_field_list|contains($field)}selected="selected"{/if} {if $field|eq('email')}disabled="disabled"{/if}>{$field}</option>
										{/foreach}
									</select>
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
													<input class="button" type="submit" name="ExportButton" value="{'Export'|i18n( 'newsletter/subscription/export' )}" title="{'Export subscriptions.'|i18n( 'newsletter/subscription/export' )}" />
													<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'newsletter/subscription/export' )}" title="{'Cancel subscription export.'|i18n( 'newsletter/subscription/export' )}" />
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
