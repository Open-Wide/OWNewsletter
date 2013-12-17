<?php

$Module = array( "name" => "OW Newsletter" );

$ViewList = array();

$ViewList['index'] = array(
    'script' => 'index.php',
    'functions' => array( 'index' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( ) );


$FunctionList['index'] = array();
$FunctionList['admin'] = array();