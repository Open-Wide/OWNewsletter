{def $newsletter_root_node_id = ezini( 'NewsletterSettings', 'RootFolderNodeId', 'newsletter.ini' )}
{def $page_uri = 'newsletter/index'
     $limit = 10}

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
                                                <h1 class="context-title">{'Newsletter dashboard'|i18n( 'newsletter/index' )}</h1>
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
                                                {def $newsletter_system_node_list = fetch('content', 'tree', hash( 
														'parent_node_id', $newsletter_root_node_id,
														'class_filter_type', 'include',
														'class_filter_array', array( 'newsletter_system' ),
														'sort_by', array( 'name', true() ),
													) )}
                                                {foreach $newsletter_system_node_list as $newsletter_system_node}
                                                    {include uri='design:newsletter/index/dashboard.tpl'
															 name='NlSystemBox'
															 newsletter_system_node=$newsletter_system_node}
                                                {/foreach}
                                                {undef $newsletter_system_node_list}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {def $last_edition_node_list = fetch( 'content', 'tree', hash( 'parent_node_id', $newsletter_root_node_id,
								'class_filter_type', 'include',
								'class_filter_array', fetch( 'newsletter', 'edition_class_identifer_list' ),
								'limit', $limit,
								'offset', $view_parameters.offset,
								'sort_by', array( 'modified', false() )
							) )
						 $last_edition_node_list_count = fetch( 'content', 'tree_count', hash( 
								'parent_node_id', $newsletter_root_node_id,
								'class_filter_type', 'include',
								'class_filter_array', fetch( 'newsletter', 'edition_class_identifer_list' ),
							) )}
                    <div class="content-view-children">
                        <div class="context-block">
                            <div class="box-header">
                                <div class="box-tc">
                                    <div class="box-ml">
                                        <div class="box-mr">
                                            <div class="box-tl">
                                                <div class="box-tr">
                                                    <h2 class="context-title">{'Last actions'|i18n( 'newsletter/index' )}</h2>
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
                                                    {if $last_edition_node_list_count|gt(0)}
                                                        {include uri = 'design:newsletter/index/last_actions.tpl'
															 name = 'EditionList'
															 edition_node_list = $last_edition_node_list
															 edition_node_list_count = $last_edition_node_list_count
															 show_actions_colum = false()}
                                                        <div class="context-toolbar subitems-context-toolbar">
                                                            {include name = 'Navigator'
																 uri = 'design:navigator/google.tpl'
																 page_uri = $page_uri
																 item_count = $last_edition_node_list_count
																 view_parameters = $view_parameters
																 item_limit = $limit}
                                                        </div>
                                                    {else}
                                                        {'No action'|i18n('newsletter/index')}
                                                    {/if}
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
        </div>
        <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>
</div>

