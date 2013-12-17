<?php

$FunctionList = array();
$FunctionList['edition_class_list'] = array(
	'name' => 'list',
	'operation_types' => array(
		'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'fetchEditionClassList' ),
	'parameter_type' => 'standard',
	'parameters' => array()
);

$FunctionList['edition_class_identifer_list'] = array(
	'name' => 'list',
	'operation_types' => array(
		'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'fetchEditionClassIdentifierList' ),
	'parameter_type' => 'standard',
	'parameters' => array()
);
