<?php

class Application_Model_Command extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_COMMAND;

    public function addcommand($idUser) {
        try {
            $data = array(
                'id_user' => $idUser,
                'dt_command' => date('Y-m-d H:i:s'),
            );
            $this->insert($data);
            $idCommand = $this->getLastCommandUser($idUser);
        } catch (Exception $ex) {
            echo 'ERROR_INSERT_ADDCOMMAND : ' . $ex->getMessage();
            return false;
        }
        return $idCommand[0];
    }

    public function getLastCommandUser($idUser) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('c' => DB_TABLE_COMMAND))
                ->where('c.id_user = ?', $idUser)
                ->order('c.dt_command DESC')
                ->limit(1);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_GETLASCOMMANDUSER : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }
    public function getUser($idCommand){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('c' => DB_TABLE_COMMAND))
                 ->joinInner(array('u' => DB_TABLE_USER), 'u.id_user = c.id_user',array('firstname_user', 'lastname_user', 'address_user', 'phone_user', 'mail_user'))
                ->where('c.id_command = ?', $idCommand);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_getUser : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }
    
    public function getCommande($idUser){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('cl' => DB_TABLE_COMMAND_LINE))
                ->joinInner(array('c' => DB_TABLE_COMMAND), 'cl.id_command = c.id_command')
                ->joinInner(array('s' => DB_TABLE_SELL), 'cl.id_sell = s.id_sell')
                ->joinInner(array('u' => DB_TABLE_USER), 's.id_user = u.id_user')
                ->where('u.id_user = ?', $idUser);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_GETCOMMANDE : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }
    

}
