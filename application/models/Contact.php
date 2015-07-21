<?php

class Application_Model_Contact extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    public $data = array();

    //public function sendMail($nom, $prenom , $sujet, $mailUser,$message,$mailSite) {
    public function sendMail() {
        
    }

}
