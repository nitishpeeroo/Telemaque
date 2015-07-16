<?php

class Application_Model_Category extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_CATEGORY;
    public $data = array();

    public function getRubrique() {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => DB_TABLE_CATEGORY), array('a.id_category','a.label_category', 'a.parent'));
               // ->joinInner(array('i' => DB_TABLE_IMAGE_WEBSITE),'a.id_category = i.id_category');
        try {
            $tab = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETARTICLES : ' . $ex->getMessage();
            return false;
        }

        $this->data = $tab;
        return $this->data;
    }

}
