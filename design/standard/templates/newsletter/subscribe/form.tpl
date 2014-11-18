<div class="newsletter newsletter-subscribe">
    <div class="border-box">
		<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
		<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

					{def $newsletter_root_node_id = ezini( 'NewsletterSettings', 'RootFolderNodeId', 'newsletter.ini' )
						 $newsletter_system_list = fetch( 'content', 'tree', hash( 
								'parent_node_id', $newsletter_root_node_id,
								'class_filter_type', 'include',
								'class_filter_array', array( 'newsletter_system' ),
								'sort_by', array( 'name', true() ),
								'limitation', hash( )
							) )
						 $newsletter_mailing_list_count = fetch( 'content', 'tree_count', hash(
								'parent_node_id', $newsletter_root_node_id,
								'extended_attribute_filter', hash( 
									'id', 'newsletter_mailing_list_filter',
									'params', hash( 'siteaccess', array( 'current_siteaccess' ) ) 
								),
								'class_filter_type', 'include',
								'class_filter_array', array('newsletter_mailing_list'),
								'limitation', hash() 
							) )}

					<h1>{'Subscribe'|i18n( 'newsletter/subscribe' )}</h1>
					{if or( $newsletter_system_list|count()|eq(0), $newsletter_mailing_list_count|eq(0) )}
						<div class="block">
							<p>{'No newsletter available.'|i18n( 'newsletter/subscribe' )} </p>
						</div>
					{else}
                                            
                                                {if is_set( $existing_newsletter_user )}
                                                        {'You are already subscribed to our newsletter.'|i18n( 'newsletter/subscribe' )}
                                                        <br/><br/>
                                                        {'To change your subscription'|i18n( 'newsletter/subscribe' )},
                                                        <a href={concat('newsletter/configure/',$existing_newsletter_user.hash)|ezurl()}>{'follow this link.'|i18n( 'newsletter/subscribe' )}</a>
                                                {else}                                            
                                            
						<form name="subscribe" method="post" action={'/newsletter/subscribe'|ezurl()}>
							{if and( is_set( $warning_array ), $warning_array|count|ne( 0 ) )}
								<div class="block">
									<div class="message-warning">
										<h2>{'Input did not validate'|i18n('newsletter/warning_message')}</h2>
										<ul>
											{foreach $warning_array as $message}
												<li><span class="text">{$message|i18n('newsletter/warning_message')}</span></li>
												{/foreach}
										</ul>
									</div>
								</div>
							{/if}


                                                        
                                                        
                                                        
                                                        
                                                        

							<div class="block header">
								<p><b>{'Subscribe now to one or more of our newsletters.'|i18n( 'newsletter/subscribe' )}</b></p>
							</div>

							{* E-mail. *}
							<p>{'* mandatory fields'|i18n( 'newsletter/subscribe' )}</p>
							<div class="block {if $attribute_warning_array|contains('email')}nl-error{/if}">
								<label for="Subscription_Email">{"E-mail"|i18n( 'newsletter/subscribe' )} *:</label>
								<input class="halfbox" id="Subscription_Email" type="text" name="NewsletterUser[email]" value="{$newsletter_user.email|email}" title="{'Your e-mail.'|i18n( 'newsletter/subscribe' )}" />
							</div>

							{* salutation *}
							<div class="block {if $attribute_warning_array|contains('salutation')}nl-error{/if}" id="nl-salutation">
								<label>{"Salutation"|i18n( 'newsletter/subscribe' )} {if $required_fields|contains('salutation')}*{/if}:</label>
								{foreach $available_salutation_array as $salutation_id => $salutation_name}
									<input type="radio" name="NewsletterUser[salutation]" value="{$salutation_id|wash}"{if and( is_set( $newsletter_user.salutation ), $newsletter_user.salutation|eq( $salutation_id ) )} checked="checked"{/if} title="{$salutation_name|wash}" />{$salutation_name|wash}&nbsp;
								{/foreach}
							</div>

							{* First name. *}
							<div class="block {if $attribute_warning_array|contains('first_name')}nl-error{/if}">
								<label for="Subscription_FirstName">{"First name"|i18n( 'newsletter/subscribe' )} {if $required_fields|contains('first_name')}*{/if}:</label>
								<input class="halfbox" id="Subscription_FirstName" type="text" name="NewsletterUser[first_name]" value="{$newsletter_user.first_name|wash}" title="{'Your first name.'|i18n( 'newsletter/subscribe' )}" />
							</div>

							{* Last name. *}
							<div class="block {if $attribute_warning_array|contains('last_name')}nl-error{/if}">
								<label for="Subscription_LastName">{"Last name"|i18n( 'newsletter/subscribe' )} {if $required_fields|contains('last_name')}*{/if}:</label>
								<input class="halfbox" id="Subscription_LastName" type="text" name="NewsletterUser[last_name]" value="{$newsletter_user.last_name|wash}" title="{'Your last name.'|i18n( 'newsletter/subscribe' )}" />
							</div>

							{def $additional_fields = fetch('newsletter', 'user_additional_fields')}
							{foreach $additional_fields as $field_identifier => $field_configuration}
								{include uri=concat('design:newsletter/additional_fields/',$field_configuration.type,'.tpl') field_identifier=$field_identifier field_configuration=$field_configuration newsletter_user=$newsletter_user attribute_warning_array=$attribute_warning_array}
							{/foreach}
							<div class="block {if $attribute_warning_array|contains('subscription_list')}nl-error{/if}">
								{foreach $newsletter_system_list as $newsletter_system}
									{def $newsletter_mailing_list_list = fetch( 'content', 'tree', hash(
											'parent_node_id', $newsletter_system.node_id,
											'extended_attribute_filter', hash( 
												'id', 'newsletter_mailing_list_filter',
												'params', hash( 'siteaccess', array( 'current_siteaccess' ) ) ),
											'class_filter_type', 'include',
											'class_filter_array', array('newsletter_mailing_list'),
											'limitation', hash() 
										) )}
									{if $newsletter_mailing_list_list|count()|gt(0)}
										<h2>{attribute_view_gui attribute=$newsletter_system.data_map.title}</h2>
										<table border="0" width="100%">
											{foreach $newsletter_mailing_list_list as $newsletter_mailing_list sequence array( 'bglight', 'bgdark' ) as $style}
												{def $newsletter_mailing_list_id = $newsletter_mailing_list.contentobject_id}
												<tr>
													<td valign="top" class="newsletter-list">
														<input type="hidden" name="NewsletterUser[mailing_list][]" value="{$newsletter_mailing_list_id}" />
														{if $newsletter_mailing_list_list_count|eq(1)}
															<input type="checkbox" name="NewsletterUser[subscription_list][]" value="{$newsletter_mailing_list_id}" checked="checked" title="{$newsletter_mailing_list.data_map.title.content|wash}" /> {$newsletter_mailing_list.data_map.title.content|wash}
														{else}
															<input type="checkbox" name="NewsletterUser[subscription_list][]" value="{$newsletter_mailing_list_id}"{if $newsletter_user.subscription_list|contains( $newsletter_mailing_list_id )} checked="checked"{/if} title="{$newsletter_mailing_list.data_map.title.content|wash}" /> {$newsletter_mailing_list.data_map.title.content|wash}
														{/if}
													</td>
												</tr>
												{undef $newsletter_mailing_list_id}
											{/foreach}
										</table>
									{/if}
									{undef $newsletter_mailing_list_list}
								{/foreach}
							</div>

							<div class="block">
								<input type="hidden" name="RedirectUrlActionCancel" value="{$redirect_url_action_cancel}" />
								<input type="hidden" name="RedirectUrlActionSuccess" value="{$redirect_url_action_success}" />
								<input class="button" type="submit" name="SubscribeButton" value="{'Subscribe'|i18n( 'newsletter/subscribe' )}" title="{'Add to subscription.'|i18n( 'newsletter/subscribe' )}" />
								<a href={$node_url|ezurl()}><input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'newsletter/subscribe' )}" /></a>
							</div>


							<div class="block footer">
								<h3>{'Data Protection'|i18n( 'newsletter/subscribe' )}:</h3>
								<p>{'Your e-mail address will under no circumstances be passed on to unauthorized third parties.'|i18n( 'newsletter/subscribe' )}</p>
							</div>
						</form>
                                                {/if}      
					{/if}
				</div></div></div>
		<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>
</div>

