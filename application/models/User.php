<?php

class Application_Model_User extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_USER;
    
    public $data = array();

    public function login($login, $password) {
        try {
            
            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('u' => DB_TABLE_USER))
                    ->joinInner(array('r' => DB_TABLE_RANK)
                            ,'u.level_user = r.id_rank')
                    ->where('u.login_user = ?', $login)
                    ->where('u.password_user = ?', $password);
            $row = $this->fetchAll($select)->toArray();
            
            $this->data = $row[0];
        
            $flag = false;
            if (sizeof($this->data) > 0) {
                $flag = $this->data;
            }
        } 
        catch (Exception $ex) {
            echo $ex->getMessage();
         
        }
        return $flag;
    }

    public function inscription($login, $password, $firstname, $lastname, $mail, $phone, $address) {
        try {
            $data = array(
                'login_user' => $login,
                'password_user' => $password,
                'lastname_user' => $lastname,
                'firstname_user' => $firstname,
                'mail_user' => $mail,
                'phone_user' => $phone,
                'address_user' => $address,
                'level_user' => 1
            );
            $flag = false;

            if ($this->insert($data)) {
                $flag = true;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $flag;
    }
    
}