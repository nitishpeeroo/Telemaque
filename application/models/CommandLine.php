<?php

class Application_Model_Commandline extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_COMMAND_LINE;

    public function addcommandLine($idCommand, $panier) {
        $flag = true;
        $article= new Application_Model_Sell();
        foreach ($panier as $idProduct => $qte) {
            $price = $article->getArticle($idProduct, array());
            try {
                $data = array(
                    'id_command' => $idCommand,
                    'id_sell' => $idProduct,
                    'quantity' => $qte,
                    'price' => $price[0]['price']
                );
                $this->insert($data);
            } catch (Exception $ex) {
                echo 'ERROR_INSERT_ADDCOMMANDLINE : ' . $ex->getMessage();
                return false;
            }
        }
        return $flag;   
       
    }

    public function getligneCommmand($idCommand) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('cl' => DB_TABLE_COMMAND_LINE), array('quantity'))
                ->joinInner(array('c' => DB_TABLE_COMMAND), 'cl.id_command = c.id_command')
                ->joinInner(array('s' => DB_TABLE_SELL), 's.id_sell = cl.id_sell',array('title','price'))
                ->where('c.id_command =?', $idCommand);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_SELECT_GETFACTURE : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }
    
    public function getCommmandLineSell($idUser) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('cl' => DB_TABLE_COMMAND_LINE))
                ->joinInner(array('c' => DB_TABLE_COMMAND), 'cl.id_command = c.id_command')
                ->joinInner(array('s' => DB_TABLE_SELL), 's.id_sell = cl.id_sell')
                ->joinInner(array('cat' => DB_TABLE_CATEGORY), 's.id_category = cat.id_category')
                ->where('c.id_user =?', $idUser);
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_SELECT_GETCOMMANDLINESELL : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }
    
    
}
