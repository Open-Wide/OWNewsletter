<?php

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();

/*
  $contextCreateNewsletterUser = false;

  $subscriptionDataArr = array(
  'first_name' => '',
  'last_name' => '',
  'organisation' => '',
  'email' => '',
  'salutation' => '',
  'note' => '',
  'id_array' => array(),
  'list_array' => array(),
  'list_output_format_array' => array()
  );

  $warningArr = array();

  $redirectUrlCancel = $redirectUrlStore = 'newsletter/user_list';

  if ( $http->hasVariable( 'RedirectUrlActionCancel' ) ) {
  $redirectUrlCancel = $http->variable( 'RedirectUrlActionCancel' );
  } elseif ( $http->hasVariable( 'RedirectUrl' ) ) {
  $redirectUrlCancel = $http->variable( 'RedirectUrl' );
  }

  if ( $http->hasVariable( 'RedirectUrlActionStore' ) ) {
  $redirectUrlStore = $http->variable( 'RedirectUrlActionStore' );
  } elseif ( $http->hasVariable( 'RedirectUrl' ) ) {
  $redirectUrlStore = $http->variable( 'RedirectUrl' );
  }


  // set data from POST for new and existing users
  if ( $http->hasPostVariable( 'Subscription_Email' ) ) {
  $subscriptionDataArr['email'] = trim( $http->postVariable( 'Subscription_Email' ) );
  }
  if ( $http->hasPostVariable( 'Subscription_FirstName' ) ) {
  $subscriptionDataArr['first_name'] = trim( $http->postVariable( 'Subscription_FirstName' ) );
  }
  if ( $http->hasPostVariable( 'Subscription_LastName' ) ) {
  $subscriptionDataArr['last_name'] = trim( $http->postVariable( 'Subscription_LastName' ) );
  }
  if ( $http->hasPostVariable( 'Subscription_Organisation' ) ) {
  $subscriptionDataArr['organisation'] = trim( $http->postVariable( 'Subscription_Organisation' ) );
  }
  if ( $http->hasPostVariable( 'Subscription_Salutation' ) ) {
  $subscriptionDataArr['salutation'] = trim( $http->postVariable( 'Subscription_Salutation' ) );
  }
  if ( $http->hasPostVariable( 'Subscription_Note' ) ) {
  $subscriptionDataArr['note'] = trim( $http->postVariable( 'Subscription_Note' ) );
  }
  if ( $http->hasPostVariable( 'Subscription_IdArray' ) ) {
  $subscriptionDataArr['id_array'] = $http->postVariable( 'Subscription_IdArray' );
  }
  if ( $http->hasPostVariable( 'Subscription_ListArray' ) ) {
  $subscriptionDataArr['list_array'] = $http->postVariable( 'Subscription_ListArray' );
  }

  //   $subscriptionDataArr['list_output_format_array'] = array();

  foreach ( $subscriptionDataArr['id_array'] as $listId ) {
  if ( $http->hasPostVariable( "Subscription_OutputFormatArray_$listId" ) ) {
  $subscriptionDataArr['list_output_format_array'][$listId] = $http->postVariable( "Subscription_OutputFormatArray_$listId" );
  } else {
  $defaultOutputFormatId = 0;
  $subscriptionDataArr['list_output_format_array'][$listId] = array(
  $defaultOutputFormatId );
  }
  }

  // validate data if new user will be created
  if ( $module->isCurrentAction( 'CreateEdit' ) ) {

  $newsletterUserId = -1;
  $msg = 'edit_new';

  $requiredSubscriptionFields = array(
  'email' );
  foreach ( $requiredSubscriptionFields as $fieldName ) {
  switch ( $fieldName ) {
  case 'email': {
  if ( !eZMail::validate( $subscriptionDataArr['email'] ) || $subscriptionDataArr['email'] == '' ) {
  $warningArr['email'] = array(
  'field_key' => ezpI18n::tr( 'newsletter/subscription', 'Email' ),
  'message' => ezpI18n::tr( 'newsletter/subscription', 'You must provide a valid email address.' ) );
  } else {
  // check if email already exists
  $existingNewsletterUserObject = OWNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );

  if ( is_object( $existingNewsletterUserObject ) ) {
  // If email exists redirect to user_edit
  $newsletterUserId = $existingNewsletterUserObject->attribute( 'id' );
  $msg = 'edit_existing';
  }
  }
  } break;
  default:
  }
  }

  // only store changes if all is ok
  if ( $module->isCurrentAction( 'CreateEdit' ) && count( $warningArr ) == 0 ) {
  // rerun with all postData
  $rerunUrl = 'newsletter/user_edit/' . $newsletterUserId;
  $newPostArray = array_merge( $oldPostArray, $_POST );
  if ( isset( $newPostArray['OldPostVarSerialized'] ) ) {
  unset( $newPostArray['OldPostVarSerialized'] );
  }

  $_POST = array();
  $_POST = $newPostArray;
  $_POST['UserCreateMsg'] = $msg;
  $_POST['StoreDraftButton'] = 'storedraft';
  $Result['rerun_uri'] = $rerunUrl;

  return $module->setExitStatus( eZModule::STATUS_RERUN );
  }
  } elseif ( $module->isCurrentAction( 'Cancel' ) ) {
  $module->redirectTo( $redirectUrlCancel );
  }

  $tpl->setVariable( 'subscription_data_array', $subscriptionDataArr );

  $tpl->setVariable( 'warning_array', $warningArr );


  $tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
  $tpl->setVariable( 'redirect_url_action_store', $redirectUrlStore );
  $tpl->setVariable( 'subscription_data', $subscriptionDataArr );
 */

/* Get views parameters */
$viewParameters = array( 'status' => FALSE, 'offset' => 0 );
if ( is_array( $Params['UserParameters'] ) ) {
	$viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}
$tpl->setVariable( 'view_parameters', $viewParameters );

/* Initilize module result */
$Result = array();
$Result['path'] = array(
	array(
		'url' => 'newsletter/index',
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Newsletter' ) ),
	array(
		'url' => 'newsletter/user',
		'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Users' ) ) );

/* Retrieval of cancal and store redirect URLs */
$redirectUrlCancel = $redirectUrlStore = 'newsletter/user';
if ( $module->hasActionParameter( 'RedirectUrlActionCancel' ) ) {
	$redirectUrlCancel = $module->actionParameter( 'RedirectUrlActionCancel' );
}
if ( $module->hasActionParameter( 'RedirectUrlActionStore' ) ) {
	$redirectUrlStore = $module->actionParameter( 'RedirectUrlActionStore' );
}
$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_store', $redirectUrlStore );

/* If press Cancel button */
if ( $module->hasActionParameter( 'Cancel' ) ) {
	$module->redirectTo( $redirectUrlCancel );
}

/* Initilize newsletter user row data */
$newsletterUserRow = array(
	'first_name' => '',
	'last_name' => '',
	'organisation' => '',
	'email' => '',
	'salutation' => '',
	'note' => '',
	'status' => OWNewsletterUser::STATUS_CONFIRMED,
	'id_array' => array(),
	'mailing_list_array' => array()
);
if ( $Params['newsletterUserID'] && is_numeric( $Params['newsletterUserID'] ) ) {
	$newsletterUser = OWNewsletterUser::fetch( $Params['newsletterUserID'] );
	$newsletterUserRow['id'] = $Params['newsletterUserID'];
}

/* If submit a newsletter user form (new or edit) */
if ( $module->hasActionParameter( 'NewsletterUser' ) ) {
	$newsletterUserData = $module->actionParameter( 'NewsletterUser' );
	foreach ( $newsletterUserData as $data => $value ) {
		switch ( $data ) {
			case 'first_name':
			case 'last_name':
			case 'organisation' :
			case 'email' :
			case 'salutation' :
				$newsletterUserRow[$data] = trim( $value );
				break;
			case 'note' :
			case 'subscription_list':
				$newsletterUserRow[$data] = $value;
				break;
		}
	}
	try {
		$newsletterUserObject = OWNewsletterUser::createOrUpdate( $newsletterUserRow, 'user_edit' );
		$newsletterUserObject->updateSubscriptionList( $newsletterUserRow['subscription_list'], 'user_edit' );
	} catch ( Exception $e ) {
		$error = $e->getMessage();
	}
	if ( $newsletterUserObject instanceof OWNewsletterUser ) {
		$tpl->setVariable( 'subscription_array', $newsletterUserObject->attribute( 'subscription_array' ) );
	}
	if ( isset( $error ) ) {
		$tpl->setVariable( 'warning_array', array( $error ) );
	} else {
		$module->redirectTo( $redirectUrlStore );
	}
}
/* If press SubmitNewsletterUser button to access or validate form */
if ( $module->isCurrentAction( 'SubmitNewsletterUser' ) ) {
	if ( isset( $newsletterUser ) ) { /* edit user */
		$tpl->setVariable( 'newsletter_user', $newsletterUser );
		$tpl->setVariable( 'subscription_array', $newsletterUser->attribute( 'subscription_array' ) );
		$Result['path'][] = array(
			'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'Edit' ) );
	} else { /* new user */
		$tpl->setVariable( 'newsletter_user', $newsletterUserRow );
		$Result['path'][] = array(
			'text' => ezpI18n::tr( 'design/admin/parts/ownewsletter/menu', 'New' ) );
	}
	$tpl->setVariable( 'available_salutation_array', OWNewsletterUser::getAvailablesSalutationsFromIni() );
	$Result['content'] = $tpl->fetch( 'design:newsletter/user/form.tpl' );
} elseif ( isset( $newsletterUser ) ) { /* show user */
	$tpl->setVariable( 'newsletter_user', $newsletterUser );
	$Result['path'][] = array(
		'text' => $newsletterUser->attribute( 'name' ) );
	$Result['content'] = $tpl->fetch( 'design:newsletter/user/show.tpl' );
} else { /* all other case : list users */
	$Result['content'] = $tpl->fetch( 'design:newsletter/user/list.tpl' );
}




