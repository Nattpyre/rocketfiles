<?php

use Core\Model;
use Helpers\Database;

class Model_User extends Model {
    	
    function __construct()
    {
    	parent::__construct();
    }

    public function checkUserExists($id) 
    {
    	return $this->db->select("SELECT * FROM rf_users WHERE id = :id", 
		array(':id' => $id), PDO::FETCH_ASSOC);
    }

    public function checkUserName($userName)
    {
        return $this->db->select("SELECT * FROM rf_users WHERE login = :login", 
        array(':login' => $userName), PDO::FETCH_ASSOC);
    }

    public function checkUserCookie($userName)
    {
        return $this->db->select("SELECT id, password FROM rf_users WHERE login = :login", 
        array(':login' => $userName), PDO::FETCH_ASSOC);
    }

    public function registerUser($userName, $email, $password)
    {
        $data = array(
            'login'    => $userName,
            'email'    => mb_strtolower($email),                                 
            'password' => $password                            
        );

        return $this->db->insert('rf_users', $data);
    }

    public function updateUser($email, $password, $userID)
    {
        $data = array(
            'email' => $email,
            'password' => $password
            );

        $where = array('id' => $userID);

        $this->db->update('rf_users', $data, $where);
    }

    public function deleteUser($userID)
    {
        $data = array('id' => $userID);

        $this->db->delete('rf_users', $data);
    }

    public function getUserPassword($login) 
    {
        return $this->db->select("SELECT id, password FROM rf_users WHERE login = :login", 
        array(':login' => $login), PDO::FETCH_ASSOC);
    }

    public function sendPassword($email)
    {
        $result = $this->db->select("SELECT * FROM rf_users WHERE email = :email", 
        array(':email' => $email), PDO::FETCH_ASSOC);

        if(empty($result)) {
            return 'Пользователь с таким email не найден.';
        }

        $login = $result[0]['login'];

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $newPassword = substr(str_shuffle($chars), 0, 6);

        $data = array('password' => \Helpers\Password::make($newPassword));
        $where = array('login' => $login);
        $this->db->update('rf_users', $data, $where);

        $mail = new \Helpers\PhpMailer\mail();
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('robot@rocketfiles.zz.mu');
        $mail->addAddress($email);
        $mail->subject('Восстановление пароля');
        $mail->body('<p>Новый пароль для пользователя <b>' . $login . '</b>: <b>' . $newPassword . '</b></p><br>Сообщение сгенерировано роботом, отвечать на него не нужно.');
        $mail->send();

        return 'Сообщение успешно отправлено!';
    }

    public function checkEmail($email)
    {
        return $this->db->select("SELECT * FROM rf_users WHERE email = :email", 
        array(':email' => $email), PDO::FETCH_ASSOC);
    }

    public function deleteFile($userName, $fileID)
    {
        $result = $this->db->select("SELECT id, user_name, file_name, server_name FROM rf_files WHERE id = :fileID AND user_name = :userName", 
        array(':fileID' => $fileID, ':userName' => $userName), PDO::FETCH_ASSOC);

        if(!empty($result)) {
            if(unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $result[0]['server_name'])) {

                $data = array('id' => $fileID);
                $this->db->delete('rf_files', $data);

                $commentData = array('file_id' => $fileID);
                $this->db->delete('rf_comments', $commentData);
                
                return 'Файл успешно удален';
            } else {
                return 'Ошибка при удалении файла';
            }
        }
    }
}