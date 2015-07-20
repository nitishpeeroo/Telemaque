<?php

class Application_Model_Sell extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_SELL;
    public $data = array();

    public function addSell($title, $image, $quantity, $category, $price, $descritptionCourt, $descritption, $idUser) {
        try {
            $data = array(
                'id_user' => $idUser,
                'title' => $title,
                'quantity' => $quantity,
                'id_category' => $category,
                'price' => $price,
                'description_courte' => $descritptionCourt,
                'description' => $descritption,
                'is_checked' => 0,
                'dt_creation' => date('Y-m-d H:i:s'),
                'dt_modification' => date('Y-m-d H:i:s'),
            );
            $this->insert($data);
            $idSell = $this->getLastUserIdSell($title, $idUser, $category);
        } catch (Exception $ex) {
            echo 'ERROR_INSERT_ADDSELL : ' . $ex->getMessage();
            return false;
        }
        return $idSell;
    }

    public function getUserSell($idUser) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('s' => DB_TABLE_SELL))
                ->joinInner(array('c' => DB_TABLE_CATEGORY)
                        , 's.id_category = c.id_category')
                ->joinInner(array('i' => DB_TABLE_IMAGE)
                        , 's.id_sell = i.id_sell')
                ->where('id_user = ?', $idUser);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_SELECT_GETARTICLE : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }

    public function getLastUserIdSell($title, $idUser, $category) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('s' => DB_TABLE_SELL))
                ->where('title = ?', $title)
                ->where('id_user = ?', $idUser)
                ->where('id_category = ?', $category);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_SELECT_GETARTICLE : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data[0]['id_sell'];
    }

    public function searchArticle($label) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('s' => DB_TABLE_SELL))
                ->joinInner(array('i' => DB_TABLE_IMAGE), 's.id_sell = i.id_sell')
                ->where('s.title like "%' . $label . '%"');
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_SELECT_GETSELL : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }

    public function countArticle($article) {
        return (count($article));
    }

    public function getArticle($id = null, $tab = array()) {
        
        if (count($tab) > 0) {
            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('s' => DB_TABLE_SELL))
                    ->joinInner(array('i' => DB_TABLE_IMAGE), 's.id_sell = i.id_sell')
                    ->joinInner(array('u' => DB_TABLE_USER), 's.id_user = u.id_user')
                    ->where('s.id_sell IN (?)', $tab);
          
        } else {
            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('s' => DB_TABLE_SELL))
                    ->joinInner(array('i' => DB_TABLE_IMAGE), 's.id_sell = i.id_sell')
                    ->joinInner(array('u' => DB_TABLE_USER), 's.id_user = u.id_user')
                    ->where('s.id_sell = ?', $id);
        }
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_SELECT_GETARTICLE : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }

    public function getNewsArticles($nb) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('s' => DB_TABLE_SELL))
                ->joinInner(array('i' => DB_TABLE_IMAGE), 's.id_sell = i.id_sell')
                ->order('s.dt_creation')
                ->limit($nb);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_SELECT_GETARTICLE : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }

    public function convertImageSousRubrique($ssrubrique) {
        foreach ($ssrubrique as &$p) {
            $p['img64'] = base64_encode($p['image']);
            $p['type'] = pathinfo($p['name_image'], PATHINFO_EXTENSION);
        }
        return $ssrubrique;
    }

    public function updateSell($title, $image, $quantity,$price, $descritptionCourt, $descritption, $idSell) {
        try {
            $data = array(
                'title' => $title,
                'quantity' => $quantity,
                'price' => $price,
                'description_courte' => $descritptionCourt,
                'description' => $descritption,
                'dt_modification' => date('Y-m-d H:i:s'),
            );
            $this->update($data,'id_sell = ' . $idSell);
        } catch (Exception $ex) {
            echo 'ERROR_INSERT_ADDSELL : ' . $ex->getMessage();
            return false;
        }
    }
    
    public function updatePriceQuantity($quantity,$price,$idSell) {
        try {
            $data = array(
                'quantity' => $quantity,
                'price' => $price,
            );
            $this->update($data,'id_sell = ' . $idSell);
        } catch (Exception $ex) {
            echo 'ERROR_INSERT_ADDSELL : ' . $ex->getMessage();
            return false;
        }
    }
}
