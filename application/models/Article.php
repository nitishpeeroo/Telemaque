<?php

class Application_Model_Article extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_ARTICLE;
    public $data = array();

    /* Retourne la liste des articles */

    public function getArticles() {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => DB_TABLE_ARTICLE), array(
            'a.id_article',
            'a.label_article'
        ));

        try {

            $tab = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETARTICLES : ' . $ex->getMessage();
            return false;
        }

        $this->data = $tab;
        return $this->data;
    }

    /* Retourne l'ID de l'article */

    public function getArticle($id) {

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(DB_TABLE_ARTICLE)
                ->where('id_article = ?', $id);

        try {

            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETARTICLE : ' . $ex->getMessage();
            return false;
        }

        $this->data = $row;
        return $this->data;
    }

    /* Ajoute un nouvel article */

    public function addArticle($label) {

        try {

            $this->insert(array(
                'label_article' => $label
            ));
        } catch (Exception $ex) {

            echo 'ERROR_INSERT_ADDARTICLE : ' . $ex->getMessage();
            return false;
        }

        return true;
    }

    /* Modifie l'article */

    public function updateArticle($id, $label) {

        try {

            $this->update(array(
                'label_article' => $label
                    ), 'id_article = ' . $id);
        } catch (Exception $ex) {

            echo 'ERROR_UPDATE_UPDATEARTICLE : ' . $ex->getMessage();
            return false;
        }

        return true;
    }

    /* Supprime l'article */

    public function deleteArticle($id) {

        try {

            $nbRowsDeleted = $this->delete('id_article = ' . $id);
        } catch (Exception $ex) {

            echo 'ERROR_DELETE_DELETEARTICLE : ' . $ex->getMessage();
            return false;
        }

        return $nbRowsDeleted;
    }

    public function searchArticle($label) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('s' => DB_TABLE_SELL))
                ->joinInner(array('i' => DB_TABLE_IMAGE) ,'s.id_sell = i.id_sell')
                ->where('s.title like "%'.$label.'%"');
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {

            echo 'ERROR_SELECT_GETSELL : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }
    public function countArticle($article){
        return (count($article));
    }
    
    public function getUserArticle($idUser) {

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

}
