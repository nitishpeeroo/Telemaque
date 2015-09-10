<?php

class Application_Model_General extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_GENERAL;
    public $data = array();

    /* Retourne la liste des articles */

    public function getGeneral() {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('g' => DB_TABLE_GENERAL));

        try {

            $tab = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETGENERAL : ' . $ex->getMessage();
            return false;
        }

        $this->data = $tab;
        return $this->data;
    }
    
    public function veriStatUser($ns)
    {       
        $result = 0;   
        if(!empty($ns) and $ns != null) {          
            $user = new Application_Model_User();                
            $Leveluser = $user->getLevel_user($ns['id_user']); //Renvoi le role de l'utilisateur      
            
            switch ($Leveluser['level_user']) {
                case '1':
                    $result = 1;
                    break;
                case '2':
                    $result = 2;
                    break;
            }
            $val = $user->getIs_blocked($ns['id_user']);
            if($val['is_blocked'] == '1') { //Si l'utilisateur est blocké
                $result = 3;
            }     
        }      
     
        return $result;
    }
    
    public function getSlider()
    {       
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from('slider')
                ->where('is_active = ?',1)
                ->order('position');

        try {

            $tab = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETGENERAL : ' . $ex->getMessage();
            return false;
        }

        $this->data = $tab;
        return $this->data;
    }
}
