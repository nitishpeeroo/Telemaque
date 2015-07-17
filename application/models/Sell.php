<?php

class Application_Model_Sell extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_SELL;
    public $data = array();

    public function addSell($title,$image,$quantity,$category,$price,$descritptionCourt,$descritption, $idUser) {

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
            
            $idSell = $this->getLastUserIdSell($title,$idUser,$category);
            
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
                            ,'s.id_category = c.id_category')
                ->joinInner(array('i' => DB_TABLE_IMAGE)
                            ,'s.id_sell = i.id_sell')
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
    
    public function getLastUserIdSell($title,$idUser,$category) {

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

}