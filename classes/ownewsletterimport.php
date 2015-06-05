<?php

class OWNewsletterImport extends eZPersistentObject {

    const STATUS_NO_ERROR = 0;
    const STATUS_ERROR = 1;
    const WIDTHOUT_FILE_HEADER = 0;
    const WIDTH_FILE_HEADER = 1;
    const IMPORT_NO_PROCESSED = 0;
    const IMPORT_PROCESSED = 1;

    /**
     * Constructor
     *
     * @param array $row
     * @return void
     */
    function __construct($row = array()) {
        $this->eZPersistentObject($row);
    }

    // import nouvelle ligne avec date, fichier, ligne de texte, column_delimiter
    // fetch pour voir si des imports sont commandés
    // Modification d'une ligne (date process, error, message error)

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
                'created' => array(
                    'name' => 'Created',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'creator_contentobject_id' => array(
                    'name' => 'CreatorContentObjectId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'processed' => array(
                    'name' => 'Processed',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false),
                'mailing_list_id' => array(
                    'name' => 'MailingLstId',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'has_processed' => array(
                    'name' => 'HasProcessed',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false),
                'file' => array(
                    'name' => 'File',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true),
                'column_delimiter' => array(
                    'name' => 'ColumnDelimiter',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true),
                'file_header' => array(
                    'name' => 'FileHeader',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true),
                'has_error' => array(
                    'name' => 'HasError',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false),
                'message_error' => array(
                    'name' => 'MessageError',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true)
            ),
            'keys' => array('id'),
            'increment_key' => 'id',
            'function_attributes' => array(
                'has_processed' => 'hasProcessed',
                'has_error' => 'hasError',
            ),
            'class_name' => 'OWNewsletterImport',
            'name' => 'ownl_import');
    }

    /*     * **********************
     * FUNCTION ATTRIBUTES
     * ********************** */

    function hasProcessed() {
        $status = $this->attribute('has_processed',true);
        return $status == self::IMPORT_PROCESSED ? true : false;
    }

    function hasError() {
        $status = $this->attribute('has_error',true);
        return $status == self::STATUS_ERROR ? true : false;
    }

    function getCreatorUserObject() {
        if ($this->attribute('creator_contentobject_id',true) != 0) {
            $user = eZUser::fetch( $this->attribute('creator_contentobject_id',true) ); 
            return $user;
        } else {
            return false;
        }
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
     * Returns object by eZ User Id
     *
     * @return NewsletterUser / boolean
     */
    static function fetchByProcessed() {
        $objects = eZPersistentObject::fetchObjectList(self::definition(), null, array('has_processed' => self::IMPORT_NO_PROCESSED));
        return $objects;
    }

    public function setProcessed() {
        if ($this->attribute('id') > 1) {
            $this->setAttribute('processed', time());
        } else {
            $this->setAttribute('processed', $this->attribute('created'));
        }
        $this->setAttribute('has_processed', self::IMPORT_PROCESSED);
        $this->setAttribute('created_contentobject_id', eZUser::currentUserID());
        $this->store();
    }

    public function setError($hasError, $messageError) {
        if ($hasError == self::STATUS_ERROR) {
            $this->setAttribute('has_error', self::STATUS_ERROR);
            $this->setAttribute('message_error', $messageError);
        } else {
            $this->setAttribute('has_error', self::STATUS_NO_ERROR);
            $this->setAttribute('message_error', $messageError);
        }
        $this->store();
    }

    /**
     *
     * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
     */
    function setAttribute($attr, $value) {
        return eZPersistentObject::setAttribute($attr, $value);
    }

    /************************
     * PERSISTENT METHODS
     * ********************** */

    /**
     * Create new import object
     *
     * @param array $dataArray
     * @return object
     */
    static function createOrUpdate($dataArray) { 
        
        self::validateImportData($dataArray);
       
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
            'has_processed' => self::IMPORT_NO_PROCESSED,
                ), $dataArray);

        $object = new self($row);
        $object->store();
        return $object;
    }

    /**
     * Check if the data passed to create or update a import are correct
     * 
     * @param array $dataArray
     * @throw InvalidArgumentException
     */
    public static function validateImportData($dataArray) {
        if (!isset($dataArray['file']) || empty($dataArray['file'])) {
            throw new InvalidArgumentException('File is missing ' );
        }
        
        if (!isset($dataArray['mailing_list_id']) || empty($dataArray['mailing_list_id'])) {
            throw new InvalidArgumentException('MailingListId is missing ' );
        }        

        if (!isset($dataArray['column_delimiter'])) {
            throw new InvalidArgumentException('Column delimiter is missing');
        }

        if (!isset($dataArray['file_header'])) {
            throw new InvalidArgumentException('File header is missing');
        }
    }

}
