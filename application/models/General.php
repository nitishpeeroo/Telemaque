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
}
