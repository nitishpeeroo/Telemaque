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
                            , 'u.level_user = r.id_rank')
                    ->where('u.login_user = ?', $login)
                    ->where('u.password_user = ?', md5($password));
            $row = $this->fetchAll($select)->toArray();

            $this->data = $row[0];

            $flag = false;
            if (sizeof($this->data) > 0) {
                $flag = $this->data;
            }
        } catch (Exception $ex) {
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
                            , 'u.level_user = r.id_rank')
                    ->where('u.id_user = ?', $id);
            $row = $this->fetchAll($select)->toArray();

            $this->data = $row[0];

            $flag = false;
            if (sizeof($this->data) > 0) {
                $flag = $this->data;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $flag;
    }

    public function inscription($login, $password, $firstname, $lastname, $mail, $phone, $address, $cp, $ville) {
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

    public function updateUser($password, $firstname, $lastname, $mail, $phone, $address, $ville, $cp, $id) {
        try {
            if ($password == '') {
                $data = array(
                    'lastname_user' => $lastname,
                    'firstname_user' => $firstname,
                    'mail_user' => $mail,
                    'phone_user' => $phone,
                    'address_user' => $address,
                    'ville_user' => $ville,
                    'codepostal_user' => $cp,
                );
            } else {
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
            if ($this->update($data, 'id_user = ' . $id) != -1) {

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
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function verifMdp($lastMdp, $id) {
        try {

            $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('u' => DB_TABLE_USER))
                    ->where('u.id_user = ?', $id);
            $row = $this->fetchAll($select)->toArray();
            $this->data = $row[0];
            if ($this->data['password_user'] == md5($lastMdp)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUserByCommandLine($id_command) {
//select cl.id_command, u.address_user, u.mail_user, u.codepostal_user, u.ville_user, u.phone_user
//from user u
//inner join command c on c.id_user = u.id_user
//inner join command_line cl on cl.id_command = c.id_command
//where cl.id_command in (16,17,18)
//group by cl.id_command

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('u' => DB_TABLE_USER), array('address_user', 'mail_user', 'codepostal_user', 'ville_user', 'phone_user'))
                ->joinInner(array('c' => DB_TABLE_COMMAND), 'c.id_user = u.id_user')
                ->joinInner(array('cl' => DB_TABLE_COMMAND_LINE), 'cl.id_command = c.id_command', array('id_command'))
               ->where('cl.id_command IN (?)', $id_command)
                ->group('cl.id_command');
        try {
            $row = $this->fetchAll($select)->toArray();
        } catch (Exception $ex) {
            echo 'ERROR_getUserByCommandLine : ' . $ex->getMessage();
            return false;
        }
        $this->data = $row;
        return $this->data;
    }

}
