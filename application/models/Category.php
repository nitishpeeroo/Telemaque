<?php

class Application_Model_Category extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_CATEGORY;
    public $data = array();

    public function getRubrique() {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('c' => DB_TABLE_CATEGORY), array('c.id_category', 'c.label_category', 'c.parent'))
                ->where('c.parent = 0 ');
        try {
            $tab = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETARTICLES : ' . $ex->getMessage();
            return false;
        }
        $this->data = $tab;
        return $this->data;
    }

    public function getSousRubrique() {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('c' => DB_TABLE_CATEGORY), array('c.id_category', 'c.label_category', 'c.parent'))
                ->joinInner(array('i' => DB_TABLE_IMAGE_RUBRIQUE), 'c.id_category = i.id_category')
                ->where('c.parent !=  0 ')
                ->order('c.parent');
        try {
            $tab = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETARTICLES : ' . $ex->getMessage();
            return false;
        }
        $this->data = $tab;
        return $this->data;
    }

    public function convertImageSousRubrique($ssrubrique) {
        foreach ($ssrubrique as &$p) {
            $p['img64'] = base64_encode($p['image']);
            $p['type'] = pathinfo($p['name_image'], PATHINFO_EXTENSION);
        }
        return $ssrubrique;
    }

    public function getArticleByCategory($categorie) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('s' => DB_TABLE_SELL), array('s.id_sell', 's.id_user', 's.id_category', 's.description', 's.price', 's.quantity', 's.is_checked', 's.title', 's.dt_creation', 's.dt_modification', 's.description_courte'))
                ->joinInner(array('c' => DB_TABLE_CATEGORY), 'c.id_category = s.id_category')
                ->joinInner(array('i' => DB_TABLE_IMAGE), 's.id_sell = i.id_sell')
                ->where('c.id_category =?', $categorie)
                ->where('s.is_checked != 2');
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
