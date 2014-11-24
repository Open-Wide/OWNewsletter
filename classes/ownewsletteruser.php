<?php

class OWNewsletterUser extends eZPersistentObject {

    /**
     *
     * @var int if newsletter user has this status he wants do get newsletter but did not confirm his email
     */
    const STATUS_PENDING = 0;

    /**
     *
     * @var int if newsletter user has this status he can get newsletter mails
     */
    const STATUS_CONFIRMED = 1;
    const STATUS_REMOVED_SELF = 3;
    const STATUS_REMOVED_ADMIN = 4;

    /**
     * @var int if nl user was deactive by a soft bounce
     */
    const STATUS_BOUNCED_SOFT = 6;

    /**
     * @var int if nl user was deactive by a hard bounce
     */
    const STATUS_BOUNCED_HARD = 7;

    /**
     * @var int if newsletter user has this status he get no emails anymore
     */
    const STATUS_BLACKLISTED = 8;

    /**
     * Constructor
     *
     * @param array $row
     * @return void
     */
    function __construct($row = array()) {
        $this->eZPersistentObject($row);
    }

    /**
     * @return void
     */
    static function definition() {
        return array(
            'fields' => array(
                'id' => array(
                    'name' => 'Id',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'email' => array(
                    'name' => 'Email',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true),
                'salutation' => array(
                    'name' => 'Salutation',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false),
                'first_name' => array(
                    'name' => 'FirstName',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => false),
                'last_name' => array(
                    'name' => 'LastName',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => false),
                'hash' => array(
                    'name' => 'Hash',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true),
                'status' => array(
                    'name' => 'Status',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'ez_user_id' => array(
                    'name' => 'EzUserId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false),
                'creator_contentobject_id' => array(
                    'name' => 'CreatorContentobjectId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'created' => array(
                    'name' => 'Created',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'modifier_contentobject_id' => array(
                    'name' => 'ModifierContentobjectId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'modified' => array(
                    'name' => 'Modified',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'confirmed' => array(
                    'name' => 'Confirmed',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'removed' => array(
                    'name' => 'Removed',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'bounced' => array(
                    'name' => 'Bounced',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'blacklisted' => array(
                    'name' => 'Blacklisted',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'note' => array(
                    'name' => 'Note',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => false),
                'remote_id' => array(
                    'name' => 'RemoteId',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => false),
                'bounce_count' => array(
                    'name' => 'BounceCount',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'serialized_data' => array(
                    'name' => 'serializedData',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => false)
            ),
            'keys' => array('id'),
            'increment_key' => 'id',
            'function_attributes' => array(
                'name' => 'getName',
                'salutation_name' => 'getSalutationName',
                'is_confirmed' => 'isConfirmed',
                'is_removed_self' => 'isRemovedSelf',
                'is_removed' => 'isRemoved',
                'is_on_blacklist' => 'isOnBlacklist',
                'subscription_list' => 'getSubscriptionArray',
                'email_name' => 'getEmailName',
                'creator' => 'getCreatorUserObject',
                'modifier' => 'getModifierUserObject',
                'ez_user' => 'getEzUserObject',
                'status_name' => 'getStatusString',
                'status_identifier' => 'getStatusIdentifier',
                'active_subscriptions' => 'getActiveSubscriptions',
                'active_mailing_list_contentobjects' => 'getActiveMailingLists',
                'active_mailing_list_contentobject_ids' => 'getActiveMailingListIDs',
                'approved_subscriptions' => 'getApprovedSubscriptions',
                'approved_mailing_list_contentobjects' => 'getApprovedMailingLists',
                'additional_fields' => 'getAdditionalFields',
                'additional_data' => 'getAdditionalData'
            ),
            'class_name' => 'OWNewsletterUser',
            'name' => 'ownl_user');
    }

    /*     * **********************
     * FUNCTION ATTRIBUTES
     * ********************** */

    /**
     * Get Name of NL User
     * use a tpl to have full flexebiltiy to render the name
     *
     * @return string
     */
    function getName() {
        $newsletterIni = eZINI::instance('newsletter.ini');
        $useTplForNameGeneration = $newsletterIni->variable('NewsletterUserSettings', 'UseTplForNameGeneration');
        if ($useTplForNameGeneration === 'enabled') {
            $tpl = eZTemplate::factory();
            $tpl->setVariable('nl_user', $this);
            $templateFile = 'design:newsletter/user/name.tpl';
            $name = strip_tags(trim($tpl->fetch($templateFile)));
            unset($tpl);
            return $name;
        } else {
            $name = trim($this->attribute('salutation_name') . ' ' . $this->attribute('first_name') . ' ' . $this->attribute('last_name'));
            return $name;
        }
    }

    /**
     * Get i18n for salutation id
     * user newsletter.ini
     * [NewsletterUserSettings]
     * SalutationMappingArray[value_1]=Mr.
     * SalutationMappingArray[value_2]=Mrs.
     *
     * so we can extent this
     * @return string
     */
    function getSalutationName() {
        $availableSalutationNameArray = self::getAvailablesSalutationsFromIni();
        $salutationId = (int) $this->attribute('salutation');
        if (array_key_exists($salutationId, $availableSalutationNameArray)) {
            return $availableSalutationNameArray[$salutationId];
        } else {
            return '';
        }
    }

    /**
     * Check if current object has status confirmed
     *
     * @return boolean
     */
    function isConfirmed() {
        $status = $this->attribute('status');
        return $status == self::STATUS_CONFIRMED ? true : false;
    }

    /**
     * Check if current object has status self removed
     *
     * @return boolean
     */
    function isRemovedSelf() {
        $status = $this->attribute('status');
        return $status == self::STATUS_REMOVED_SELF ? true : false;
    }

    /**
     * Check if current object has status removed
     *
     * @return boolean
     */
    function isRemoved() {
        $status = $this->attribute('status');
        return $status == self::STATUS_REMOVED_SELF || $status == self::STATUS_REMOVED_ADMIN ? true : false;
    }

    /**
     * Check if current user object is on blacklist
     * and if status is blacklisted
     *
     * @return boolean
     */
    function isOnBlacklist() {
        $status = $this->attribute('status');
        $isOnBlacklist = OWNewsletterBlacklistItem::isEmailOnBlacklist($this->attribute('email'));
        if ($isOnBlacklist) {
            // fix up status blacklisted if it is not set
            if ($status != OWNewsletterUser::STATUS_BLACKLISTED) {
                $this->setBlacklisted();
                return true;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Returns all subcriptions for the current user, which hasn't a REMOVE status
     *
     * @return array
     */
    function getSubscriptionArray() {
        $listSubscriptionArray = array();
        $subscriptionArray = OWNewsletterSubscription::fetchListByNewsletterUserId($this->attribute('id'));
        foreach ($subscriptionArray as $subscriptionObject) {
            $listSubscriptionArray[$subscriptionObject->attribute('mailing_list_contentobject_id')] = $subscriptionObject;
        }
        return $listSubscriptionArray;
    }

    /**
     * Returns all subcriptions for the current user, which Active status
     *
     * @return array
     */
    function getSubscriptionActiveArray() {
        $listSubscriptionArray = array();
        $subscriptionArray = OWNewsletterSubscription::fetchActiveList(array('newsletter_user_id' => (int) $this->attribute('id')));
        foreach ($subscriptionArray as $subscriptionObject) {
            $listSubscriptionArray[$subscriptionObject->attribute('mailing_list_contentobject_id')] = $subscriptionObject;
        }
        return $listSubscriptionArray;
    }

    /**
     * Return the name which will display in email  e.g. Max Mustermman
     *
     * @return string
     */
    function getEmailName() {
        $emailName = '';
        $firstName = $this->attribute('first_name');
        $lastName = $this->attribute('last_name');

        if ($firstName != '') {
            $emailName .= $firstName . ' ';
        }

        if ($lastName != '') {
            $emailName .= $lastName;
        }

        return $emailName;
    }

    /**
     * Get Creator user object
     *
     * @return unknown_type
     */
    function getCreatorUserObject() {
        if ($this->attribute('creator_contentobject_id') != 0) {
            $user = eZContentObject::fetch($this->attribute('creator_contentobject_id'));
            return $user;
        } else {
            return false;
        }
    }

    /**
     * Get user object
     *
     * @return unknown_type
     */
    function getModifierUserObject() {
        $retVal = false;
        if ($this->attribute('modifier_contentobject_id') != 0) {
            $retVal = eZContentObject::fetch($this->attribute('modifier_contentobject_id'));
        }
        return $retVal;
    }

    /**
     * Get user object
     *
     * @return eZUser object
     */
    function getEzUserObject() {
        $retVal = false;
        if ($this->attribute('ez_user_id') != 0) {
            $retVal = eZUser::fetch($this->attribute('ez_user_id'));
        }
        return $retVal;
    }

    /**
     * get a translated string for the status code
     * @return unknown_type
     */
    function getStatusString() {
        $statusString = '-';

        $availableStatusArray = self::getAvailableStatus();
        $currentStatusId = $this->attribute('status');

        if (array_key_exists($currentStatusId, $availableStatusArray)) {
            $statusString = $availableStatusArray[$currentStatusId];
        }
        return $statusString;
    }

    /**
     * get a translated string for the status code
     * @return unknown_type
     */
    function getStatusIdentifier() {
        $statusIdentifier = '-';

        $availableStatusArray = self::getAvailableStatus('identifier');
        $currentStatusId = $this->attribute('status');

        if (array_key_exists($currentStatusId, $availableStatusArray)) {
            $statusIdentifier = $availableStatusArray[$currentStatusId];
        }
        return $statusIdentifier;
    }

    /**
     * Return all active subscriptions of the user
     * 
     * @return array of OWNewsletterSubscription
     */
    function getActiveSubscriptions() {
        $conds = array(
            'newsletter_user_id' => $this->attribute('id')
        );
        return OWNewsletterSubscription::fetchActiveList($conds);
    }

    /**
     * Return all approved subscriptions of the user
     * 
     * @return array of OWNewsletterSubscription
     */
    function getApprovedSubscriptions() {
        $conds = array(
            'status' => OWNewsletterSubscription::STATUS_APPROVED,
            'newsletter_user_id' => $this->attribute('id')
        );
        return OWNewsletterSubscription::fetchList($conds);
    }

    /**
     * Return all approved mailing lists of the user
     * 
     * @return array of OWNewsletterMailingList
     */
    function getApprovedMailingLists() {
        $return = array();
        $approvedSubscriptions = $this->attribute('approved_subscriptions');
        foreach ($approvedSubscriptions as $approvedSubscription) {
            $return[$approvedSubscription->attribute('mailing_list_contentobject')->attribute('id')] = $approvedSubscription->attribute('mailing_list_contentobject');
        }
        return $return;
    }

    /**
     * Return all active mailing lists of the user
     * 
     * @return array of OWNewsletterMailingList
     */
    function getActiveMailingLists() {
        $return = array();
        $activeSubscriptions = $this->attribute('active_subscriptions');
        foreach ($activeSubscriptions as $activeSubscription) {
            $return[$activeSubscription->attribute('mailing_list_contentobject')->attribute('id')] = $activeSubscription->attribute('mailing_list_contentobject');
        }
        return $return;
    }

    /**
     * Return all active mailing lists ID of the user
     * 
     * @return array of OWNewsletterMailingList
     */
    function getActiveMailingListIDs() {
        $activeMailingLists = $this->attribute('active_mailing_list_contentobjects');
        return array_keys($activeMailingLists);
    }

    /**
     * Get additional fields from newsletter.ini
     */
    function getAdditionalFields() {
        $additionalFields = array();
        $newsletterIni = eZINI::instance('newsletter.ini');
        $iniAdditionalFieldList = $newsletterIni->variable('NewsletterUserSettings', 'AdditionalFields');
        $trans = eZCharTransform::instance();
        foreach ($iniAdditionalFieldList as $iniAdditionalField) {
            $additionalFieldGroup = "AdditionalField_$iniAdditionalField";
            if ($newsletterIni->hasGroup($additionalFieldGroup)) {
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'Label')) {
                    $additionalFields[$iniAdditionalField]['label'] = $newsletterIni->variable($additionalFieldGroup, 'Label');
                } else {
                    $additionalFields[$iniAdditionalField]['label'] = $iniAdditionalField;
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'Required')) {
                    $additionalFields[$iniAdditionalField]['required'] = $newsletterIni->variable($additionalFieldGroup, 'Required') == 'true' ? true : false;
                } else {
                    $additionalFields[$iniAdditionalField]['required'] = false;
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'Type')) {
                    $additionalFields[$iniAdditionalField]['type'] = $newsletterIni->variable($additionalFieldGroup, 'Type');
                } else {
                    $additionalFields[$iniAdditionalField]['type'] = 'string';
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'HelpMessage')) {
                    $additionalFields[$iniAdditionalField]['help_message'] = $newsletterIni->variable($additionalFieldGroup, 'HelpMessage');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'DefaultValue')) {
                    $additionalFields[$iniAdditionalField]['default_value'] = $newsletterIni->variable($additionalFieldGroup, 'DefaultValue');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'MinSelected')) {
                    $additionalFields[$iniAdditionalField]['min_selected'] = $newsletterIni->variable($additionalFieldGroup, 'MinSelected');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'MaxSelected')) {
                    $additionalFields[$iniAdditionalField]['max_selected'] = $newsletterIni->variable($additionalFieldGroup, 'MaxSelected');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'Min')) {
                    $additionalFields[$iniAdditionalField]['min'] = $newsletterIni->variable($additionalFieldGroup, 'Min');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'Max')) {
                    $additionalFields[$iniAdditionalField]['max'] = $newsletterIni->variable($additionalFieldGroup, 'Max');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'MinLenght')) {
                    $additionalFields[$iniAdditionalField]['min_lenght'] = $newsletterIni->variable($additionalFieldGroup, 'MinLenght');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'MaxLenght')) {
                    $additionalFields[$iniAdditionalField]['max_lenght'] = $newsletterIni->variable($additionalFieldGroup, 'MaxLenght');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'Format')) {
                    $additionalFields[$iniAdditionalField]['format'] = $newsletterIni->variable($additionalFieldGroup, 'Format');
                }
                if ($newsletterIni->hasVariable($additionalFieldGroup, 'SelectOptions')) {
                    $iniValues = $newsletterIni->variable($additionalFieldGroup, 'SelectOptions');
                    $fixValues = array();
                    foreach ($iniValues as $key => $name) {
                        if (is_string($key)) {
                            $fixValues[$trans->transformByGroup($key, 'identifier')] = $name;
                        } else {
                            $fixValues[$key] = $name;
                        }
                    }
                    $additionalFields[$iniAdditionalField]['select_options'] = $fixValues;
                }
                if (isset($additionalFields[$iniAdditionalField]['default_value'])) {
                    switch ($additionalFields[$iniAdditionalField]['type']) {
                        case 'integer':
                            $additionalFields[$iniAdditionalField]['default_value'] = (int) $additionalFields[$iniAdditionalField]['default_value'];
                            break;
                        case 'checkbox':
                            $additionalFields[$iniAdditionalField]['default_value'] = $additionalFields[$iniAdditionalField]['default_value'] == 'true' ? true : false;
                            break;
                        case 'multiselect':
                            $additionalFields[$iniAdditionalField]['default_value'] = explode(';', $additionalFields[$iniAdditionalField]['default_value']);
                            foreach ($additionalFields[$iniAdditionalField]['default_value'] as $index => $defaultValue) {
                                $additionalFields[$iniAdditionalField]['default_value'][$index] = $trans->transformByGroup($defaultValue, 'identifier');
                            }
                            break;
                        case 'select':
                            $additionalFields[$iniAdditionalField]['default_value'] = $trans->transformByGroup($additionalFields[$iniAdditionalField]['default_value'], 'identifier');
                            break;
                        case 'radio':
                            $additionalFields[$iniAdditionalField]['default_value'] = $trans->transformByGroup($additionalFields[$iniAdditionalField]['default_value'], 'identifier');
                            break;
                    }
                }
                if ($additionalFields[$iniAdditionalField]['type'] == 'date' && !isset($additionalFields[$iniAdditionalField]['format'])) {
                    $additionalFields[$iniAdditionalField]['format'] = 'YYYY-MM-DD';
                } elseif ($additionalFields[$iniAdditionalField]['type'] == 'datetime' && !isset($additionalFields[$iniAdditionalField]['format'])) {
                    $additionalFields[$iniAdditionalField]['format'] = 'YYYY-MM-DD HH:mm:ss';
                }
            }
        }
        return $additionalFields;
    }

    public function getAdditionalData() {
        $additionalData = unserialize($this->attribute('serialized_data'));
        if ($additionalData == false) {
            $additionalData = array();
        }
        $additionalFields = $this->getAdditionalFields();
        foreach ($additionalFields as $fieldIdentifier => $fieldConfiguration) {
            if (isset($fieldConfiguration['default_value']) && !isset($additionalData[$fieldIdentifier])) {
                $additionalData[$fieldIdentifier] = $fieldConfiguration['default_value'];
            }
        }
        return $additionalData;
    }

    public function validateAdditionalData($newAdditionalData) {
        $errors = array(
            'warning_field' => array(),
            'warning_message' => array());
        $additionalFields = $this->getAdditionalFields();
        foreach ($additionalFields as $fieldIdentifier => $fieldConfiguration) {
            if ($fieldConfiguration['required'] == true && (!isset($newAdditionalData[$fieldIdentifier]) || empty($newAdditionalData[$fieldIdentifier]) )) {
                $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : The field is required.', null, array(
                            '%fieldname' => $fieldConfiguration['label']));
            } elseif (!empty($newAdditionalData[$fieldIdentifier])) {
                switch ($fieldConfiguration['type']) {
                    case 'integer':

                        if (!is_numeric($newAdditionalData[$fieldIdentifier])) {
                            $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                            $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : The field must be an integer.', null, array(
                                        '%fieldname' => $fieldConfiguration['label']));
                        } else {
                            if (isset($fieldConfiguration['min']) && (int) $newAdditionalData[$fieldIdentifier] < $fieldConfiguration['min']) {
                                $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                                $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : The field must be greater than %value.', null, array(
                                            '%fieldname' => $fieldConfiguration['label'],
                                            '%value' => $fieldConfiguration['min']));
                            }
                            if (isset($fieldConfiguration['max']) && (int) $newAdditionalData[$fieldIdentifier] > $fieldConfiguration['max']) {
                                $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                                $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : The field" be lower than %value.', null, array(
                                            '%fieldname' => $fieldConfiguration['label'],
                                            '%value' => $fieldConfiguration['max']));
                            }
                        }
                        break;
                    case 'multiselect':
                        if (isset($fieldConfiguration['min_selected']) && count($newAdditionalData[$fieldIdentifier]) < $fieldConfiguration['min_selected']) {
                            $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                            $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : You must select at least %value values.', null, array(
                                        '%fieldname' => $fieldConfiguration['label'],
                                        '%value' => $fieldConfiguration['min_selected']));
                        }
                        if (isset($fieldConfiguration['max_selected']) && count($newAdditionalData[$fieldIdentifier]) > $fieldConfiguration['max_selected']) {
                            $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                            $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : You must select at most %value values.', null, array(
                                        '%fieldname' => $fieldConfiguration['label'],
                                        '%value' => $fieldConfiguration['max_selected']));
                        }
                        break;
                    case 'string':
                    case 'text':
                        if (isset($fieldConfiguration['min_lenght']) && strlen($newAdditionalData[$fieldIdentifier]) < $fieldConfiguration['min_lenght']) {
                            $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                            $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : You must enter a text of at least %value characters.', null, array(
                                        '%fieldname' => $fieldConfiguration['label'],
                                        '%value' => $fieldConfiguration['min_lenght']));
                        }
                        if (isset($fieldConfiguration['max_lenght']) && strlen($newAdditionalData[$fieldIdentifier]) > $fieldConfiguration['max_lenght']) {
                            $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                            $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : You must enter a text of at most %value characters.', null, array(
                                        '%fieldname' => $fieldConfiguration['label'],
                                        '%value' => $fieldConfiguration['max_lenght']));
                        }
                        break;
                    case 'date':
                    case 'datetime':
                        $initialDate = array(
                            'YYYY' => 0,
                            'MM' => 0,
                            'DD' => 0,
                            'HH' => 0,
                            'mm' => 0,
                            'ss' => 0
                        );
                        $format = $fieldConfiguration['format'];
                        $formatScanReplace = array(
                            'YYYY' => '%4s',
                            'MM' => '%2s',
                            'DD' => '%2s',
                            'HH' => '%2s',
                            'mm' => '%2s',
                            'ss' => '%2s',
                        );
                        $formatScan = str_replace(array_keys($formatScanReplace), array_values($formatScanReplace), $format);
                        $dateKeys = sscanf($format, $formatScan);

                        $valuesScanReplace = array(
                            '%4s' => '%04d',
                            '%2s' => '%02d'
                        );
                        $valueScan = str_replace(array_keys($valuesScanReplace), array_values($valuesScanReplace), $formatScan);
                        $dateValues = sscanf($newAdditionalData[$fieldIdentifier], $valueScan);
                        if (array_search(null, $dateValues) !== FALSE) {
                            $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                            $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : The date is not valid.', null, array(
                                        '%fieldname' => $fieldConfiguration['label']));
                        } else {
                            $date = array_merge($initialDate, array_combine($dateKeys, $dateValues));
                            $timestamp = mktime($date['HH'], $date['mm'], $date['ss'], $date['MM'], $date['DD'], $date['YYYY']);
                            if ($timestamp === FALSE) {
                                $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                                $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : The date is not valid.', null, array(
                                            '%fieldname' => $fieldConfiguration['label']));
                            } else {
                                $strftimeFormatReplace = array(
                                    'YYYY' => '%Y',
                                    'MM' => '%m',
                                    'DD' => '%d',
                                    'HH' => '%H',
                                    'mm' => '%M',
                                    'ss' => '%i',
                                );
                                $strftimeFormat = str_replace(array_keys($strftimeFormatReplace), array_values($strftimeFormatReplace), $format);
                                $strftimeDate = strftime($strftimeFormat, $timestamp);
                                if ($strftimeDate != $newAdditionalData[$fieldIdentifier]) {
                                    $errors['warning_field'][] = 'additional_data_' . $fieldIdentifier;
                                    $errors['warning_message'][] = ezpI18n::tr('newsletter/warning_messages', '%fieldname : The date is not valid.', null, array(
                                                '%fieldname' => $fieldConfiguration['label']));
                                }
                            }
                        }
                }
            }
        }
        if (empty($errors['warning_field'])) {
            return false;
        }
        return $errors;
    }

    public function setAdditionalData($newAdditionalData) {
        if ($this->validateAdditionalData($newAdditionalData) === false) {
            $this->setAttribute('serialized_data', serialize($newAdditionalData));
            $this->store();
            return $this;
        }
        return false;
    }

    /*     * **********************
     * FETCH METHODS
     * ********************** */

    /**
     * Returns object by id
     *
     * @param integer $id
     * @return object
     */
    static function fetch($id) {
        $object = eZPersistentObject::fetchObject(self::definition(), null, array('id' => $id), true);
        return $object;
    }

    /**
     * Search all objects with custom conditions
     *
     * @param array $conds
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchList($conds = array(), $limit = false, $offset = false, $asObject = true) {
        $sortArr = array(
            'created' => 'desc');
        $limitArr = null;

        if ((int) $limit != 0) {
            $limitArr = array(
                'limit' => $limit,
                'offset' => $offset);
        }
        $objectList = eZPersistentObject::fetchObjectList(self::definition(), null, $conds, $sortArr, $limitArr, $asObject, null, null, null, null);
        return $objectList;
    }

    /**
     * Count all object with custom conditions
     *
     * @param array $conds
     * @return interger
     */
    static function countList($conds = array()) {
        $objectList = eZPersistentObject::count(self::definition(), $conds);
        return $objectList;
    }

    /**
     * Returns object by email
     *
     * @param string $email
     * @return array / boolean
     */
    static function fetchByEmail($email) {
        $object = eZPersistentObject::fetchObject(self::definition(), null, array('email' => $email), true);
        return $object;
    }

    /**
     * Returns object by eZ User Id
     *
     * @param int $ezUserId
     * @return NewsletterUser / boolean
     */
    static function fetchByEzUserId($ezUserId) {
        if ($ezUserId > 0) {
            $object = eZPersistentObject::fetchObject(self::definition(), null, array('ez_user_id' => $ezUserId), true);
            return $object;
        }
        return false;
    }

    /**
     * Returns object by hash
     *
     * @param string $hash
     * @return object
     */
    static function fetchByHash($hash) {
        $object = eZPersistentObject::fetchObject(self::definition(), null, array('hash' => $hash), true);
        return $object;
    }

    /**
     * Fetch user by custom parameters and subscription custom parameters
     *
     * @param array $conds
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchListWithSubscription($conds, $limit = false, $offset = false, $asObject = true) {
        $sortArr = array('last_name' => 'asc', 'first_name' => 'asc',);
        $limitArr = null;

        if ((int) $limit != 0) {
            $limitArr = array(
                'limit' => $limit,
                'offset' => $offset);
        }
        $def = self::definition();
        $custom_fields = array_keys($def['fields']);
        foreach ($custom_fields as $index => $field) {
            $custom_fields[$index] = "ownl_user.$field as $field";
        }
        $custom_tables = null;
        $custom_conds = null;
        if (isset($conds['subscription'])) {
            $custom_tables = array('ownl_subscription');
            $custom_conds = ' AND ownl_user.id = ownl_subscription.newsletter_user_id';
            foreach ($conds as $field => $value) {
                if ($field != 'subscription') {
                    $conds["ownl_user.$field"] = $value;
                    unset($conds[$field]);
                }
            }
            foreach ($conds['subscription'] as $field => $value) {
                $conds["ownl_subscription.$field"] = $value;
            }
            unset($conds['subscription']);
        }
        
        $objectList = eZPersistentObject::fetchObjectList(self::definition(), array(), $conds, $sortArr, $limitArr, $asObject, null, $custom_fields, $custom_tables, $custom_conds);
        return $objectList;
    }

    /**
     * Count user by custom parameters and subscription custom parameters
     *
     * @param array $conds
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function countListWithSubscription($conds) {
        $custom_tables = null;
        $custom_conds = null;
        if (isset($conds['subscription'])) {
            $custom_tables = array('ownl_subscription');
            $custom_conds = ' AND ownl_user.id = ownl_subscription.newsletter_user_id';
            foreach ($conds['subscription'] as $field => $value) {
                $conds["ownl_subscription.$field"] = $value;
            }
            unset($conds['subscription']);
        }
        $field = '*';
        $customFields = array(array('operation' => 'COUNT( ' . $field . ' )', 'name' => 'row_count'));
        $rows = eZPersistentObject::fetchObjectList(self::definition(), array(), $conds, array(), null, false, false, $customFields, $custom_tables, $custom_conds);
        return $rows[0]['row_count'];
    }

    /*     * **********************
     * OBJECT METHODS
     * ********************** */

    /**
     * Update subscription list
     * 
     * @param array $newSubscriptionList
     */
    public function updateSubscriptionList($newSubscriptionList, $context = 'default') {
        $currentSubscriptionList = $this->attribute('subscription_list');
        foreach ($newSubscriptionList as $newSubscription) {
            if ($newSubscription['status'] > -1) {
                $newSubscription['newsletter_user_id'] = $this->attribute('id');
                try {
                    OWNewsletterSubscription::createOrUpdate($newSubscription, $context);
                } catch (Exception $e) {
                    $error = 'Failed to create or update subscription';
                }
            }
            if (isset($newSubscription['mailing_list_contentobject_id']) && isset($currentSubscriptionList[$newSubscription['mailing_list_contentobject_id']])) {
                unset($currentSubscriptionList[$newSubscription['mailing_list_contentobject_id']]);
            }
        }
        foreach ($currentSubscriptionList as $currentSubscription) {
            $currentSubscription->remove();
        }
        if (isset($error)) {
            throw new InvalidArgumentException($error);
        }
    }

    /**
     * set modified to current timestamp and set current User Id
     * if first version use created as modified timestamp
     */
    public function setModified() {
        if ($this->attribute('id') > 1) {
            $this->setAttribute('modified', time());
            $this->setAttribute('modifier_contentobject_id', eZUser::currentUserID());
        } else {
            $this->setAttribute('modified', $this->attribute('created'));
            $this->setAttribute('modifier_contentobject_id', eZUser::currentUserID());
        }
    }

    /**
     * set current object blacklisted
     * @return void
     */
    public function setBlacklisted() {
        $this->setAttribute('status', self::STATUS_BLACKLISTED);
        // set all subscriptions and all open senditems to blacklisted
        $this->store();
    }

    /**
     * Set current object non-blacklisted
     * User and subscriptions will be set to confirmed
     * @return void
     */
    public function setNonBlacklisted() {
        // we determine the actual status by checking the various timestamps
        if ($this->attribute('confirmed') != 0) {
            if ($this->attribute('bounced') != 0 || $this->attribute('removed') != 0) {
                if ($this->attribute('removed') > $this->attribute('bounced')) {
                    $this->setRemoved();
                } else {
                    $this->setBounced();
                }
            }
            // confirmed, and not deleted nor bounced
            else {
                $this->setAttribute('status', self::STATUS_CONFIRMED);
            }
        }
        // not confirmed
        else {
            // might have been removed by admin
            if ($this->attribute('removed') != 0) {
                $this->setRemoved(true);
            } else {
                $this->setAttribute('status', self::STATUS_PENDING);
            }
        }
        $this->setAttribute('blacklisted', 0);

        // set all subscriptions and all open senditems to blacklisted
        /*foreach (OWNewsletterSubscription::fetchListByNewsletterUserId($this->attribute('id')) as $subscription) {
            $subscription->setNonBlacklisted();
        }*/

        $this->store();
    }

    /**
     * Mark the user as removed
     * 
     * @param boolean $byAdmin
     * @return void
     */
    public function setRemoved($byAdmin = false) {
        if ($byAdmin == true) {
            $this->setAttribute('status', self::STATUS_REMOVED_ADMIN);
        } else {
        }
        $this->store();
    }

    /**
     * Mark the user as bounced
     * 
     * @param boolean $isHardBounce
     * @return unknown_type
     */
    public function setBounced($isHardBounce = false) {
        $newsletterIni = eZINI::instance('newsletter.ini');
        $bounceThresholdValue = (int) $newsletterIni->variable('BounceSettings', 'BounceThresholdValue');
        $userBouncCount = $this->attribute('bounce_count') + 1;
        $this->setAttribute('bounce_count', $userBouncCount);
        // set all subscriptions and all open senditems to bounced
        if ($userBouncCount >= $bounceThresholdValue) {
            if ($isHardBounce === true) {
                $this->setAttribute('status', self::STATUS_BOUNCED_HARD);
            } else {
                $this->setAttribute('status', self::STATUS_BOUNCED_SOFT);
            }
        }
        $this->store();
    }

    /**
     * Mark the user as confirmed
     * 
     * @return void
     */
    public function setConfirmed() {
        $this->setAttribute('status', self::STATUS_CONFIRMED);
        $this->store();
    }

    /**
     * set Modifed data if somebody store content
     * (non-PHPdoc)
     * @see kernel/classes/eZPersistentObject#store($fieldFilters)
     */
    public function store($fieldFilters = null) {
        $this->setModified();
        // find and set ez_user_id
        $this->findAndSetRelatedEzUserId();
        parent::store($fieldFilters);
    }

    /**
     * search the ez_user_id for the current nl email
     * @return int $ezUserId / false
     */
    public function findAndSetRelatedEzUserId() {
        $currentEzUserId = $this->attribute('ez_user_id');
        // if not set
        if ($currentEzUserId == 0) {
            $email = $this->attribute('email');
            if ($email != '') {
                $existingEzUser = eZUser::fetchByEmail($email);
                if (is_object($existingEzUser)) {
                    $ezUserId = $existingEzUser->attribute('contentobject_id');
                    $this->setAttribute('ez_user_id', $ezUserId);
                    return $ezUserId;
                }
            }
        } else {
            return $currentEzUserId;
        }

        return false;
    }

    /**
     *
     * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
     */
    function setAttribute($attr, $value) {
// TODO check if modified should be update every time a attribute is set
// may be in store method better place to do this
        switch ($attr) {
            case 'status': {
                    $currentTimeStamp = time();
                    switch ($value) {
                        case self::STATUS_CONFIRMED :
                            $this->setAttribute('confirmed', $currentTimeStamp);
                            // if a user is confirmed reset bounce count
                            $this->resetBounceCount();
                            break;

                        case self::STATUS_BOUNCED_SOFT :
                        case self::STATUS_BOUNCED_HARD :
                            $this->setAttribute('bounced', $currentTimeStamp);
                            // set all subscriptions and all open senditems to bounced
                            // see
                            // setBounced
                            break;
                        case self::STATUS_REMOVED_ADMIN :
                        case self::STATUS_REMOVED_SELF : {
                                $this->setAttribute('removed', $currentTimeStamp);
                                // set all subscriptions and all open senditems to removed
                                //
								break;
                            }
                        case self::STATUS_REMOVED_SELF :
                            $value = $this->attribute('status');
                            break;
                        case self::STATUS_BLACKLISTED :
                            $this->setAttribute('blacklisted', $currentTimeStamp);
                            // set all subscriptions and all open senditems to blacklisted
                            // see
                            // setBlacklisted
                            break;
                    }
                    return eZPersistentObject::setAttribute($attr, $value);
                } break;
            default:
                return eZPersistentObject::setAttribute($attr, $value);
        }
    }

    /**
     * set bounce_count to 0
     */
    public function resetBounceCount() {
        $this->setAttribute('bounce_count', 0);
    }

    /**
     * Mark user as confirmed
     */
    public function confirm() {
        $this->setAttribute('status', self::STATUS_CONFIRMED);
        $this->sync();
        $this->store();
    }

    /**
     * Unsubscribe from all approved subscription
     */
    public function subscribeTo($mailingListContentObjectID, $status = self::STATUS_PENDING, $context = 'default') {
        $newSubscription = array(
            'newsletter_user_id' => $this->attribute('id'),
            'mailing_list_contentobject_id' => $mailingListContentObjectID,
            'status' => $status
        );
        $subscription = OWNewsletterSubscription::createOrUpdate($newSubscription, $context);
        $subscription->setAttribute('status', $status);
        $subscription->store();
    }

    /**
     * Unsubscribe from all approved subscription
     */
    public function unsubscribeFrom($mailingListContentObjectID, $status = self::STATUS_REMOVED_SELF, $context = 'default') {
        $subscription = OWNewsletterSubscription::fetch($this->attribute('id'), $mailingListContentObjectID);
        if ($subscription instanceof OWNewsletterSubscription) {
            $subscription->unsubscribe();
        }
    }

    /**
     * Unsubscribe from all approved subscription
     */
    public function unsubscribe() {
        foreach ($this->attribute('active_subscriptions') as $subscription) {
            $subscription->unsubscribe();
        }
    }

    /**
     * remove the current newlsetter user and all depending nl subscriptions
     * @see kernel/classes/eZPersistentObject#remove($conditions, $extraConditions)
     */
    function remove($conditions = null, $extraConditions = null) {
// remove subscriptions
        $subscriptionList = $this->attribute('subscription_list');
        foreach ($subscriptionList as $subscription) {
            $subscription->remove();
        }
        $blackListItem = OWNewsletterBlacklistItem::fetchByEmail($this->attribute('email'));
        if (is_object($blackListItem)) {
            $blackListItem->setAttribute('newsletter_user_id', 0);
            $blackListItem->store();
        }
        $itemList = OWNewsletterSendingItem::fetchList(array(
                    'newsletter_user_id' => $this->attribute('id')
                ));
        foreach ($itemList as $item) {
            $item->remove();
        }
        parent::remove($conditions, $extraConditions);
    }

    public function sendConfirmationMail() {
        $mail = new OWNewsletterMail();
        $mail->sendConfirmationMail($this);
    }

    /*     * **********************
     * PERSISTENT METHODS
     * ********************** */

    /**
     * Create new OWNewsletterUser object
     *
     * @param array $dataArray
     * @param int $status
     * @return object
     */
    static function createOrUpdate($dataArray, $context = 'default') {
        self::validateNewsletterUserData($dataArray);
        $email = $dataArray['email'];
        if (isset($dataArray['id']) || !empty($dataArray['id'])) {
            $object = self::fetch($dataArray['id']);
            if ($object instanceof self) {
                foreach ($dataArray as $field => $value) {
                    if ($object->hasAttribute($field)) {
                        $object->setAttribute($field, $value);
                    }
                }
                $object->store();
                return $object;
            }
        }
        $row = array_merge(array(
            'created' => time(),
            'creator_contentobject_id' => eZUser::currentUserID(),
            'hash' => OWNewsletterUtils::generateUniqueMd5Hash($email),
            'remote_id' => 'ownl:' . $context . ':' . OWNewsletterUtils::generateUniqueMd5Hash($email),
            'status' => self::STATUS_PENDING), $dataArray);
        $object = new self($row);
        $object->setAttribute('status', $row['status']);
        if ($object->attribute('status') == self::STATUS_PENDING && $object->attribute('ez_user') !== FALSE) {
            $object->setAttribute('status', self::STATUS_CONFIRMED);
        }
        $object->store();
        return $object;
    }

    /**
     * Check if the data passed to create or update a newsletter user are correct
     * 
     * @param array $dataArray
     * @throw InvalidArgumentException
     */
    public static function validateNewsletterUserData($dataArray) {
        if (!isset($dataArray['email']) || empty($dataArray['email'])) {
            throw new InvalidArgumentException('User email is missing');
        }
        $email = $dataArray['email'];
        $emailUser = self::fetchByEmail($email);

        if (!$emailUser instanceof self) {
            return true;
        } else {
            if (isset($dataArray['id'])) {
                if ($dataArray['id'] == $emailUser->attribute('id')) {
                    return true;
                }
            }
            throw new InvalidArgumentException('A user with this email already exists');
        }
    }

    /*     * **********************
     * OTHER METHODS
     * ********************** */

    /**
     *
     * @return array[salutation_id]=>i18n
     */
    static function getAvailablesSalutationsFromIni() {
        $newsletterIni = eZINI::instance('newsletter.ini');
        $salutationMappingArray = $newsletterIni->variable('NewsletterUserSettings', 'SalutationMappingArray');
        $salutationNameArray = array();
        foreach ($salutationMappingArray as $salutationKey => $languageString) {
            $salutationKeyExplode = explode('_', $salutationKey);
            if (isSet($salutationKeyExplode[1])) {
                $salutationId = (int) $salutationKeyExplode[1];
                $salutationNameArray[$salutationId] = ezpI18n::tr('newsletter/user/salutation', $languageString);
            }
        }
        return $salutationNameArray;
    }

    /**
     * get an array of all available subscription status id with translated Names
     * @return array
     */
    static function getAvailableStatus($arrayInfo = 'name') {
        if ($arrayInfo == 'name') {
            return array(
                self::STATUS_PENDING => ezpI18n::tr('newsletter/user/status', 'Pending'),
                self::STATUS_CONFIRMED => ezpI18n::tr('newsletter/user/status', 'Confirmed'),
                self::STATUS_REMOVED_SELF => ezpI18n::tr('newsletter/user/status', 'Removed by user'),
                self::STATUS_REMOVED_ADMIN => ezpI18n::tr('newsletter/user/status', 'Removed by admin'),
                self::STATUS_BOUNCED_SOFT => ezpI18n::tr('newsletter/user/status', 'Bounced soft'),
                self::STATUS_BOUNCED_HARD => ezpI18n::tr('newsletter/user/status', 'Bounced hard'),
                self::STATUS_BLACKLISTED => ezpI18n::tr('newsletter/user/status', 'Blacklisted')
            );
        } else {
            return array(
                self::STATUS_PENDING => 'pending',
                self::STATUS_CONFIRMED => 'confirmed',
                self::STATUS_REMOVED_SELF => 'removed_by_user',
                self::STATUS_REMOVED_ADMIN => 'removed_by_admin',
                self::STATUS_BOUNCED_SOFT => 'bounced_soft',
                self::STATUS_BOUNCED_HARD => 'bounced_hard',
                self::STATUS_BLACKLISTED => 'blacklisted',
            );
        }
    }

}
