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

$FunctionList['subscription_list'] = array(
	'name' => 'list',
	'operation_types' => array(
		'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'fetchSubscriptions' ),
	'parameter_type' => 'standard',
	'parameters' => array(
		array(
			'name' => 'mailing_list_contentobject_id',
			'type' => 'integer',
			'required' => true,
			'default' => false ),
		array(
			'name' => 'status',
			'type' => 'string',
			'required' => false,
			'default' => false ) )
);

$FunctionList['subscriptions_count'] = array(
	'name' => 'list',
	'operation_types' => array(
		'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'countSubscriptions' ),
	'parameter_type' => 'standard',
	'parameters' => array(
		array(
			'name' => 'mailing_list_contentobject_id',
			'type' => 'integer',
			'required' => true,
			'default' => false ),
		array(
			'name' => 'status',
			'type' => 'string',
			'required' => false,
			'default' => false ) )
);
