{def $page_uri = 'newsletter/blacklist'}
<div class="newsletter newsletter-blacklist_list">
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Manage blacklist'|i18n( 'newsletter/blacklist')}</h1>
                                <div class="header-mainline">
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
                    <div class="context-attributes">
                        <div class="block float-break">
                            {'By adding an user to the blacklist, you can make sure that he will never get a newsletter again from this system.'|i18n('newsletter/blacklist')}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {* Buttons. *}
        <div class="controlbar">
            <div class="box-bc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tc">
                            <div class="box-bl">
                                <div class="box-br">
                                    {* Edit *}
                                    <div class="left">
                                        <form method="post" action={'newsletter/blacklist'|ezurl}>
											<input type="text" name="Email" />
                                            <input class="button" type="submit" name="AddBacklistItemButton" value="{'Add this e-mail address to blacklist'|i18n( 'newsletter/blacklist' )}" title="{'Create a new blacklist entry.'|i18n( 'newsletter/blacklist' )}" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="context-block">
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Blacklisted users'|i18n( 'newsletter/blacklist')} [{$blacklist_item_list_count}]</h2>
                                <div class="header-subline">
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
					{if $blacklist_item_list_count|gt(0)}
						<div class="context-attributes">

							<div class="context-toolbar">
								<div class="button-left">
									<p class="table-preferences">
										{switch match=$limit}
										{case match=25}
										<a href={'/user/preferences/set/admin_blacklist_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
										<span class="current">25</span>
										<a href={'/user/preferences/set/admin_blacklist_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
										{/case}
										{case match=50}
										<a href={'/user/preferences/set/admin_blacklist_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
										<a href={'/user/preferences/set/admin_blacklist_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
										<span class="current">50</span>
										{/case}
										{case}
										<span class="current">10</span>
										<a href={'/user/preferences/set/admin_blacklist_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
										<a href={'/user/preferences/set/admin_blacklist_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
										{/case}
										{/switch}
									</p>
								</div>
							</div>
							<div class="break float-break">
							</div>


							<div class="box-content">
								<table class="list" cellspacing="0">
									<tr>
										<th>
											{'E-mail'|i18n( 'newsletter/blacklist' )}
										</th>
										<th>
											{'Newsletter UID'|i18n( 'newsletter/blacklist' )}
										</th>
										<th>
											{'Created'|i18n( 'newsletter/blacklist' )}
										</th>
										<th>
											{'Creator'|i18n( 'newsletter/blacklist' )}
										</th>
										<th>
										</th>
									</tr>

									{foreach $blacklist_item_list as $blacklist_item sequence array( bglight, bgdark ) as $style}

										<tr class="{$style}">
											<td>
												{$blacklist_item.email|wash}
											</td>
											<td>
												{if $blacklist_item.newsletter_user_id|ne( 0 )}
													<a href={concat('newsletter/user/',$blacklist_item.newsletter_user_id)|ezurl}>{$blacklist_item.newsletter_user_id|wash} </a>
												{else}
													N/A
												{/if}
											</td>
											<td>
												{$blacklist_item.created|l10n( shortdatetime )|wash}
											</td>
											<td title="{$blacklist_item.creator_contentobject_id|wash}">
												{if is_object( $blacklist_item.creator )}
													{$blacklist_item.creator.name|wash}
												{/if}
											</td>
											<td>
												<form id="submit_blacklist_{$blacklist_item.id}" method="post" style="display:inline;" action={'newsletter/blacklist'|ezurl()}>
													<input type="hidden" name="RedirectUrlActionSuccess" value={$page_uri} />
													<input type="hidden" name="E-mail" value="{$blacklist_item.email|wash()}" />
													<input class="button" type="submit" name="RemoveBacklistItemButton" value="{'Remove from blacklist'|i18n( 'newsletter/blacklist' )}" />
												</form>
											</td>
										</tr>
									{/foreach}
								</table>
							</div>
							{* Navigator. *}
							<div class="context-toolbar subitems-context-toolbar">
								{include name='Navigator'
                            uri='design:navigator/google.tpl'
                            page_uri=$page_uri
                            item_count=$blacklist_item_list_count
                            view_parameters=$view_parameters
                            item_limit=$limit}
							</div>
						</div>
					{else}
						{'No blacklist item'|i18n( 'newsletter/blacklist' )}
					{/if}
                </div>
            </div>
            <div class="controlbar">
                <div class="box-bc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tc">
                                <div class="box-bl">
                                    <div class="box-br">
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