<?php

$FunctionList = array();
$FunctionList['edition_class_list'] = array(
	'name' => 'list',
	'operation_types' => array( 'read' ),
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

$FunctionList['edition_class_goup_id'] = array(
	'name' => 'list',
	'operation_types' => array(
		'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'fetchEditionClassGroupID' ),
	'parameter_type' => 'standard',
	'parameters' => array()
);

$FunctionList['user_list'] = array(
	'name' => 'list',
	'operation_types' => array( 'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'fetchUsers' ),
	'parameter_type' => 'standard',
	'parameters' => array(
		array(
			'name' => 'mailing_list_contentobject_id',
			'type' => 'integer',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'user_status',
			'type' => 'string',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'subscription_status',
			'type' => 'string',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'email',
			'type' => 'string',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'limit',
			'type' => 'integer',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'offset',
			'type' => 'integer',
			'required' => false,
			'default' => false )
	)
);

$FunctionList['user_count'] = array(
	'name' => 'list',
	'operation_types' => array( 'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'countUsers' ),
	'parameter_type' => 'standard',
	'parameters' => array(
		array(
			'name' => 'mailing_list_contentobject_id',
			'type' => 'integer',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'user_status',
			'type' => 'string',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'subscription_status',
			'type' => 'string',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'email',
			'type' => 'string',
			'required' => false,
			'default' => false ),
	)
);

$FunctionList['subscription_list'] = array(
	'name' => 'list',
	'operation_types' => array( 'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'fetchSubscriptions' ),
	'parameter_type' => 'standard',
	'parameters' => array(
		array(
			'name' => 'mailing_list_contentobject_id',
			'type' => 'integer',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'subscription_status',
			'type' => 'string',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'limit',
			'type' => 'integer',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'offset',
			'type' => 'integer',
			'required' => false,
			'default' => false ) )
);

$FunctionList['subscription_count'] = array(
	'name' => 'list',
	'operation_types' => array( 'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'countSubscriptions' ),
	'parameter_type' => 'standard',
	'parameters' => array(
		array(
			'name' => 'mailing_list_contentobject_id',
			'type' => 'integer',
			'required' => false,
			'default' => false ),
		array(
			'name' => 'subscription_status',
			'type' => 'string',
			'required' => false,
			'default' => false ), )
);

$FunctionList['available_subscription_status'] = array(
	'name' => 'list',
	'operation_types' => array( 'read' ),
	'call_method' => array(
		'class' => 'OWNewsletterFunctionCollection',
		'method' => 'fetchAvailableSubscriptionStatus' ),
	'parameter_type' => 'standard',
	'parameters' => array()
);
