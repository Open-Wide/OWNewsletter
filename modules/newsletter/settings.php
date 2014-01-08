<?php

$module = $Params["Module"];

$currentSiteAccess = $GLOBALS['eZCurrentAccess'];
$currentSiteAccessName = $currentSiteAccess['name'];

$redirectUri = "/settings/view/$currentSiteAccessName/newsletter.ini";
return $module->redirectTo( $redirectUri );