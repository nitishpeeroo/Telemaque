<?php

class Application_Model_Category extends Zend_Db_Table_Abstract {
    
    protected $_dbname  = DB_NAME_TELEMAQUE;
    protected $_name    = DB_TABLE_CATEGORY;
    public $data        = array();
    
    
}