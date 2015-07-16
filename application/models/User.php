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
    
    public function getUserData($id) {
        try {
            
            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('u' => DB_TABLE_USER))
                    ->joinInner(array('r' => DB_TABLE_RANK)
                            ,'u.level_user = r.id_rank')
                    ->where('u.id_user = ?', $id);
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
                //'phone_user' => $phone,
                //'address_user' => $address,
                'phone_user' => '0062623',
                'address_user' => '95 rue 952',
                'level_user' => 1
            );
            return $this->insert($data);
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        
    }
    
    public function updateUser($password, $firstname, $lastname, $mail, $phone, $address,$id) {
        try {
            if($password == '')
            {
                $data = array(
                    'lastname_user' => $lastname,
                    'firstname_user' => $firstname,
                    'mail_user' => $mail,
                    'phone_user' => $phone,
                    'address_user' => $address,
                );
            }
            else
            {
                $data = array(
                    'password_user' => $password,
                    'lastname_user' => $lastname,
                    'firstname_user' => $firstname,
                    'mail_user' => $mail,
                    'phone_user' => $phone,
                    'address_user' => $address,
                );
            }
            $result = false;  
            if ($this->update($data,'id_user = '. $id) != -1) {
                
                $result = $this->getUserData($id);
                
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $result;
    }
    
    
    public function loginExist($login) {
        try {
            
            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('u' => DB_TABLE_USER))   
                    ->where('u.login_user = ?', $login);
            $row = $this->fetchAll($select)->toArray();
            $this->data = $row[0];      
            $flag = false;
            return sizeof($this->data);
        } 
        catch (Exception $ex) {
            echo $ex->getMessage();
         
        }
        
    }
    
}