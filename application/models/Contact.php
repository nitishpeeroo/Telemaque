<?php

class Application_Model_Contact extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_CONTACT;
    public $data = array();

    public function sendMail($nom, $prenom , $object, $mailUser, $message) {
        
        try {
            $data = array(
                'lastname_contact' => $nom,
                'firstname_contact' => $prenom,
                'object' => $object,
                'mail_contact' => $mailUser,
                'message' => $message,
                'dt_send' => date('Y-m-d H:i:s'),
            );
            $this->insert($data);
        } catch (Exception $ex) {
            echo 'ERROR_INSERT_ADDCONTACT : ' . $ex->getMessage();
            return false;
        }
        return true;
    }

}
