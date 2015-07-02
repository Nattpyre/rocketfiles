<?php
namespace Controllers;

use Core\View;
use Core\Controller;
use Helpers\GetId3\GetId3Core as GetId3;

class Files extends Controller
{

    protected $_files;
    protected $_user;

    public function __construct()
    {
        parent::__construct();
        $this->_files = $this->loadModel('model_files');
        $this->_user = $this->loadModel('model_user');
        $this->checkCookie();
    }

    public function index()
    {
        $totalFiles = $this->_files->countTotalFiles();

        if(!empty($_GET['p']) && ctype_digit($_GET['p'])) {
            $lastFiles = $this->_files->getLastFiles($_GET['p']);
        } else {
            $lastFiles = $this->_files->getLastFiles('1');
        }

        if(isset($_GET['search']) && !empty($_GET['search'])) {

            $countSearchResults = $this->_files->countSearchResults($_GET['search']);
            $totalSearchResults = $countSearchResults[0]['COUNT(id)'];

            if(!empty($_GET['p']) && ctype_digit($_GET['p'])) {
                $searchResult = $this->_files->searchFiles($_GET['search'], $_GET['p']);
            } else {
                $searchResult = $this->_files->searchFiles($_GET['search'], '1');
            }
        }

        $pages = new \Helpers\Paginator('5','p');
        $pages->setTotal($totalFiles);

        $searchPages = new \Helpers\Paginator('5', 'p');
        $searchPages->setTotal($totalSearchResults);

        $data['title'] = 'Файлы';
        $data['lastfiles'] = $lastFiles;
        $data['search'] = $searchResult;
        $data['pageLinks'] = $pages->pageLinks();
        $data['searchPageLinks'] = $searchPages->pageLinks();
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('files/index', $data);
        View::renderTemplate('footer', $data);
    }

    public function file()
    {
        $fileID = array_keys($_GET);
        $fileID = str_replace('files/', '', $fileID[0]);

        $fileInfo = $this->_files->getFileInfo($fileID);

        if(empty($fileInfo)) {
            header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");

            $data['title'] = '404';
            $data['error'] = "Страница не найдена.";

            View::render('Error/404');
            die();
        }

        $data['title'] = $fileInfo['file_name'];
        $data['fileinfo'] = $fileInfo;

        if($data['fileinfo']['file_type'] == 'image/jpg' || $data['fileinfo']['file_type'] == 'image/gif' || $data['fileinfo']['file_type'] == 'image/png') {
            $data['imageinfo'] = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $data['fileinfo']['server_name']);
        }

        if($data['fileinfo']['file_type'] == 'image/jpg' || $data['fileinfo']['file_type'] == 'image/tiff') {
            $data['imageinfo']['saved'] = exif_read_data($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $data['fileinfo']['server_name']);
        }

        $fileType = array_shift(explode('/', $data['fileinfo']['file_type']));

        if($fileType == 'video' || $fileType == 'audio') {
            $getID3 = new GetId3();
            $data['mediainfo'] = $getID3
                                ->setEncoding('UTF-8')
                                ->analyze($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $data['fileinfo']['server_name'])
                                ;
        }

        $data['comments'] = $this->_files->getAllComments($fileID);
        $data['token'] = $this->makeToken();

        View::renderTemplate('header', $data);
        View::render('files/file', $data);
        View::renderTemplate('footer', $data);
    }

    public function addComment()
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = array(
            'secret' => '6LdLrwgTAAAAAAEJDZFkTIj_6BvsxtaFurUV4N00', 
            'response' => $_POST['g-recaptcha-response']
            );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            )
        );

        $context  = stream_context_create($options);
        $result = json_decode(file_get_contents($url, false, $context), true);

        if($result['success'] != TRUE) {
            echo 'Капча введена неверно.';
            die();
        }

        if($_POST['token'] != $_COOKIE['token']) {
            echo 'Проверьте введенные данные.';
            die();
        }

        \Helpers\GUMP::set_field_name('comment', 'Комментарий');

        $validated = \Helpers\GUMP::is_valid($_POST, array(
            'comment' => 'required|max_len,512'
            )); 

        if(is_array($validated)) {
           echo $validated[0];
           die();
        }

        $fileID = array_keys($_GET);
        $fileID = str_replace('files/', '', $fileID[0]);

        if(!empty($_SESSION['rf_user'])) {
            $userName = $_SESSION['rf_user'];
        } else {
            $userName = 'Аноним';
        }

        $date = time();

        $result = $this->_files->addComment($fileID, $userName, $date, $_POST['comment']);
    }

    public function sendAppeal()
    {
        \Helpers\GUMP::set_field_name('appealtext', 'Дополнительная информация');

        $validated = \Helpers\GUMP::is_valid($_POST, array(
            'appeal-type' => 'required',
            'appealtext' => 'max_len,512',
            'fileid' => 'required'
            ));

        if(is_array($validated)) {
           echo $validated[0];
           die();
        } 

        $mail = new \Helpers\PhpMailer\mail();
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('robot@rocketfiles.zz.mu');
        $mail->addAddress('nattpyre@gmail.com');
        $mail->subject('Новая жалоба');
        $mail->body(
            '<p>Жалоба на файл: ' . 'http://rocketfiles.com/files/' . \Helpers\Data::html($_POST['fileid']) . '</p>' .
            '<p>Причина: ' . \Helpers\Data::html($_POST['appeal-type']) . '</p>' .
            '<p>Дополнительный текст: ' . \Helpers\Data::html($_POST['appealtext']) . '</p>');
        $mail->send();

        echo 'Жалоба отправлена!';
        die();
    }

    public function upload()
    {
        if(!isset($_POST['terms'])) {
            echo 'Перед загрузкой файлов необходимо прочитать и принять условия пользования.';
            die();
        }

        if(empty($_FILES['uploadFile']['tmp_name']) && empty($_POST['remote_url'])) {
            echo 'Выберите файл для загрузки!';
            die();
        }

        if(empty($_FILES) && ($_SERVER['CONTENT_LENGTH'] > 52428800)) {
            echo 'Размер файла превышает максимум!';
            die();
        }

        if(!empty($_FILES['uploadFile']['tmp_name'])) {

            if($_FILES['uploadFile']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['uploadFile']['error'] === UPLOAD_ERR_FORM_SIZE) {

                echo 'Размер файла превышает максимум!';
                die();

            } elseif ($_FILES['uploadFile']['error'] === UPLOAD_ERR_PARTIAL) {

                echo 'Файл поврежден!';
                die();

            } elseif ($_FILES['uploadFile']['error'] === UPLOAD_ERR_NO_FILE) {

                echo 'Файл не был загружен!';
                die();

            } elseif ($_FILES['uploadFile']['error'] === UPLOAD_ERR_NO_TMP_DIR) {

                echo 'Отсутствует временная папка. Обратитесь к администратору.';
                die();

            } elseif ($_FILES['uploadFile']['error'] === UPLOAD_ERR_CANT_WRITE) {

                echo 'Ошибка при записи файла. Обратитесь к администратору.';
                die();

            } elseif ($_FILES['uploadFile']['error'] === UPLOAD_ERR_EXTENSION) {

                echo 'Загрузка файла была остановлена. Обратитесь к администратору.';
                die();

            }

            $fileName = strtolower(str_replace(' ', '_', $_FILES['uploadFile']['name']));
            $fileName = \Helpers\Data::toTranslite($fileName);
            $fileSize = $_FILES['uploadFile']['size'];
        }

        if(!empty($_POST['remote_url'])) {

            $checkURL = \Helpers\GUMP::is_valid($_POST, array(
                'remote_url' => 'valid_url'));

            if(is_array($checkURL)) {
                echo $checkURL[0];
                die();
            }

            $fileSize = \Helpers\SimpleCurl::remotefileSize($_POST['remote_url']);

            if(is_numeric($fileSize) == FALSE || $fileSize == 0) {
                echo 'Не удалось определить размер удаленного файла.';
                die();
            }

            if($fileSize > 52428800) {
                echo 'Размер удаленного файла превышает 50 МБ';
                die();
            }

            $fileName = array_pop(explode('/', $_POST['remote_url']));

            if(!preg_match('/.+\.[a-z0-9]{2,}$/ui', $fileName)) {
                echo 'Неверный формат удаленного файла.';
                die();
            }

            $fileName = strtolower(\Helpers\Data::toTranslite($fileName));
        }

        $fileExtension = \Helpers\Document::getExtension($fileName);
        $fileType = \Helpers\Document::getFileType($fileExtension);
        $mimeType = $fileType . '/' . $fileExtension;

        $serverFileName = uniqid() . '_' . $fileName . '.txt';

        if(!empty($_SESSION['rf_user'])) {
            $userName = $_SESSION['rf_user'];
        } else {
            $userName = 'Аноним';
        }

        $currentDate = time();
        
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

        if(!empty($_FILES['uploadFile']['tmp_name'])) {

            if(!move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadPath . $serverFileName)) {
                echo 'Ошибка при перемещении файла.';
                die();
            }
        }

        if(!empty($_POST['remote_url'])) {
            if(!copy($_POST['remote_url'], $uploadPath . $serverFileName)) {
                echo 'Не удалось загрузить файл с удаленного сервера.';
                die();
            }
        }

        $result = $this->_files->uploadFile($userName, $fileName, $serverFileName, $fileSize, $currentDate, $mimeType);

        if(ctype_digit($result)) {
            echo $result;
            die();
        }
    }

    public function download()
    {
        if(ctype_digit($_GET['id']) == FALSE) {
            return FALSE;
        }

        $names = $this->_files->getFileName($_GET['id']);

        if(empty($names)) {
            return FALSE;
        }

        $this->_files->increaseDownloadCounter($_GET['id']);
        $serverNamePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $names[0]['server_name'];
        $downloadName = '[rocket_files]' . $names[0]['file_name'];

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $downloadName);
        header('Expires: 0');
        header('Content-Length: ' . filesize($serverNamePath));
        readfile($serverNamePath);
        die();
    }

    public static function makePreview($img, $width, $height)
    {
        $imgPreviewName = substr_replace(mb_substr($img, 0, -4), '_preview', -4, 0);

        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $imgPreviewName)) {
            return $imgPreviewName;
        }

        $imageInfo = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $img);
        $imageWidth = $imageInfo[0];
        $imageHeight = $imageInfo[1];
        $imageType = $imageInfo[2];

        if($imageWidth <= $width && $imageHeight <= $height) {
            return $img;
        }

        if($imageWidth >= $imageHeight) {
            $ratio = $width / $imageWidth;
            $previewWidth = $width;
            $previewHeight = round($imageHeight * $ratio);
        } else {
            $ratio = $height / $imageHeight;
            $previewHeight = $height;
            $previewWidth = round($imageWidth * $ratio);
        }

        if($imageType == IMAGETYPE_JPEG) {
            $imgSource = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $img);
        } elseif ($imageType == IMAGETYPE_PNG) {
            $imgSource = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $img);
        } elseif ($imageType == IMAGETYPE_GIF) {
            $imgSource = imagecreatefromgif($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $img);
        }

        $imgPreview = imagecreatetruecolor($previewWidth, $previewHeight);

        if($imageType == IMAGETYPE_JPEG) {

            imagecopyresampled($imgPreview, $imgSource, 0, 0, 0, 0, $previewWidth, $previewHeight, $imageWidth, $imageHeight);
            imagejpeg($imgPreview, $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $imgPreviewName, 100);

        } elseif ($imageType == IMAGETYPE_PNG) {

            imagesavealpha($imgPreview, true);
            $transparent = imagecolorallocatealpha($imgPreview, 0, 0, 0, 127);
            imagefill($imgPreview, 0, 0, $transparent);

            imagecopyresampled($imgPreview, $imgSource, 0, 0, 0, 0, $previewWidth, $previewHeight, $imageWidth, $imageHeight);
            imagepng($imgPreview, $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $imgPreviewName, 1);

        } elseif ($imageType == IMAGETYPE_GIF) {

            imagecopyresampled($imgPreview, $imgSource, 0, 0, 0, 0, $previewWidth, $previewHeight, $imageWidth, $imageHeight);
            imagegif($imgPreview, $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $imgPreviewName);

        }

        return $imgPreviewName;
    }
}