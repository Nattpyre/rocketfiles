<?php

use Core\Model;
use Helpers\Database;

class Model_Files extends Model {
    	
    function __construct()
    {
    	parent::__construct();
    }

    public function countTotalFiles()
    {
        $result = $this->db->select("SELECT COUNT(id) FROM rf_files", array(), PDO::FETCH_ASSOC);
        return $result[0]['COUNT(id)'];
    }

    public function getLastFiles($pageNum)
    {
        $page = ($pageNum - 1) * 5;

        return $this->db->select("SELECT id, file_name, user_name, file_size, upload_date FROM rf_files ORDER BY 
            upload_date DESC LIMIT :page, 5", 
            array(':page' => $page), PDO::FETCH_ASSOC);
    }

    public function searchFiles($str, $pageNum)
    {
        $page = ($pageNum - 1) * 5;

        return $this->db->select("SELECT id, file_name, user_name, file_size, upload_date FROM rf_files WHERE file_name 
            LIKE :string ORDER BY upload_date DESC LIMIT :page, 5", array(
            ':string' => '%' . $str . '%', ':page' => $page), PDO::FETCH_ASSOC);
    }

    public function countSearchResults($str)
    {
        return $this->db->select("SELECT COUNT(id) FROM rf_files WHERE file_name LIKE :string", 
            array(':string' => '%' . $str . '%'), PDO::FETCH_ASSOC);
    }

    public function uploadFile($userName, $fileName, $serverFileName, $fileSize, $currentDate, $mimeType)
    {
        $data = array(
            'user_name' => $userName,
            'file_name' => $fileName,
            'server_name' => $serverFileName,
            'file_size' => $fileSize,
            'upload_date' => $currentDate,
            'file_type' => $mimeType
        );

        return $this->db->insert('rf_files', $data);
    }

    public function deleteFile($fileID) {

        $result = $this->db->select("SELECT id, user_name, file_name, server_name FROM rf_files WHERE id = :fileID", 
        array(':fileID' => $fileID), PDO::FETCH_ASSOC);

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

    public function getFileInfo($fileID)
    {
        $result = $this->db->select("SELECT * FROM rf_files WHERE id = :id", array(
            ':id' => $fileID), PDO::FETCH_ASSOC);

        return $result[0];
    }

    public function getFileName($fileID)
    {
        return $this->db->select("SELECT file_name, server_name FROM rf_files WHERE id = :id", array(
            ':id' => $fileID), PDO::FETCH_ASSOC);
    }

    public function getAllComments($fileID)
    {
        return $this->db->select("SELECT id, user_name, `date`, comment FROM rf_comments WHERE file_id = :file_id 
            ORDER BY `date` DESC", array(
            ':file_id' => $fileID), PDO::FETCH_ASSOC);
    }

    public function addComment($fileID, $username, $date, $comment)
    {
        $data = array(
            'file_id' => $fileID,
            'user_name' => $username,
            'date' => $date,
            'comment' => $comment
            );

        return $this->db->insert('rf_comments', $data);
    }

    public function deleteComment($commentID)
    {
        $result = $this->db->select("SELECT * FROM rf_comments WHERE id = :commentID", 
        array(':commentID' => $commentID), PDO::FETCH_ASSOC);

        if(!empty($result)) {
            $commentData = array('id' => $commentID);
            $this->db->delete('rf_comments', $commentData);
        }
    }

    public function increaseDownloadCounter($fileID)
    {
        $counter = $this->db->select("SELECT total_downloads FROM rf_files WHERE id = :id", array(
            ':id' => $fileID), PDO::FETCH_ASSOC);

        $data = array('total_downloads' => ++$counter[0]['total_downloads']);
        $where = array('id' => $fileID);

        $this->db->update('rf_files', $data, $where);
    }

    public function showUserFiles($user_name)
    {
        return $this->db->select("SELECT id, file_name, file_size, upload_date FROM rf_files WHERE user_name = :user_name
         ORDER BY upload_date DESC"
        , array(':user_name' => $user_name), PDO::FETCH_ASSOC);
    }
}