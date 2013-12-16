<?php

$Module = array( "name" => "OW Newsletter" );

$ViewList = array();

$ViewList['index'] = array(
    'script' => 'index.php',
    'functions' => array( 'index' ),
    'default_navigation_part' => 'ownesletter',
    'params' => array( ) );


$FunctionList['index'] = array();
$FunctionList['admin'] = array();