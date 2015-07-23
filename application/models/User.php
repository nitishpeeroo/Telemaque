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
                    ->where('u.password_user = ?', md5($password));
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
    

    public function inscription($login, $password, $firstname, $lastname, $mail, $phone, $address,$cp,$ville) {
        try {
            $data = array(
                'login_user' => $login,
                'password_user' => md5($password),
                'lastname_user' => $lastname,
                'firstname_user' => $firstname,
                'mail_user' => $mail,
                'codepostal_user' => $cp,
                'ville_user' => $ville,
                'phone_user' => $phone,
                'address_user' => $address,
                'level_user' => 1
            );
            return $this->insert($data);
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        
    }
    
    public function updateUser($password, $firstname, $lastname, $mail, $phone, $address,$ville,$cp,$id) {
        try {
            if($password == '')
            {
                $data = array(
                    'lastname_user' => $lastname,
                    'firstname_user' => $firstname,
                    'mail_user' => $mail,
                    'phone_user' => $phone,
                    'address_user' => $address,
                    'ville_user' => $ville,
                    'codepostal_user' => $cp,
                );
            }
            else
            {
                $data = array(
                    'password_user' => md5($password),
                    'lastname_user' => $lastname,
                    'firstname_user' => $firstname,
                    'mail_user' => $mail,
                    'phone_user' => $phone,
                    'address_user' => $address,
                    'ville_user' => $ville,
                    'codepostal_user' => $cp,
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
    
    public function verifMdp($lastMdp,$id)
    {
        try {
            
            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('u' => DB_TABLE_USER))   
                    ->where('u.id_user = ?', $id);
            $row = $this->fetchAll($select)->toArray();
            $this->data = $row[0];   
            if($this->data['password_user'] == md5($lastMdp)) {
                return true;
            }
            else { return false;}
        } 
        catch (Exception $ex) {
            echo $ex->getMessage();
         
        }
    }
    
}