<?php

$module = $Params[ 'Module' ];
$http = eZHTTPTool::instance();

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '' );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'current_siteaccess', $viewParameters );
$Result = array();
$Result['content'] = $tpl->fetch( "design:newsletter/index.tpl" );
    $Result['left_menu'] = 'design:parts/newsletter/menu.tpl';
$Result['path'] = array( array( 'url'  => false,
                                'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
                         array( 'url'  => false,
                                'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Dashboard' ) ) );