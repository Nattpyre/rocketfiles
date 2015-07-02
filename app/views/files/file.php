<main>
      <div class="container">
          <div class="file-info-block main-block center-block clearfix">
          <h3>

         <?php 

         $mimeType = $data['fileinfo']['file_type'];
         $fileType = array_shift(explode('/', $mimeType));
         $fileExtension = array_pop(explode('/', $mimeType));

         switch($fileType) {
              case 'image':
                echo '<i class="fa fa-file-image-o"></i>';
                break;
              case 'video':
                echo '<i class="fa fa-file-video-o"></i>';
                break;
              case 'audio':
                echo '<i class="fa fa-file-audio-o"></i>';
                break;
              case 'archive':
                echo '<i class="fa fa-file-archive-o"></i>';
                break;
              case 'document':
                echo '<i class="fa fa-file-text-o"></i>';
                break;
              default:
                echo '<i class="fa fa-file-o"></i>';
         }

          echo \Helpers\Data::html($data['fileinfo']['file_name']); 

          ?>

          </h3>

          <?php

          if ($mimeType == 'image/jpg' || $mimeType == 'image/png' || $mimeType == 'image/gif') {

            echo '<img class="img-responsive center-block" src="../uploads/' . \Helpers\Data::html(Controllers\Files::makePreview($data['fileinfo']['server_name'], 719, 540)) . '">';

          } elseif($mimeType == 'image/bmp' || $mimeType == 'image/svg') {

            echo '<img class="img-responsive center-block" src="../uploads/' . \Helpers\Data::html($data['fileinfo']['server_name']) . '">';

          }elseif ($mimeType == 'video/webm' || $mimeType == 'video/mp4' || $mimeType == 'video/ogv') {

            echo '<div class="embed-responsive embed-responsive-16by9 video-preview">';
            echo '<video class="embed-responsive-item" controls>';
            echo '<source src="../../uploads/' . \Helpers\Data::html($data['fileinfo']['server_name']) . '">';
            echo '</video>';
            echo '</div>';

          } elseif ($mimeType == 'audio/mp3' || $mimeType == 'audio/ogg' || $mimeType == 'audio/wav' || $mimeType == 'audio/aac') {

            echo '<audio class="audio-player" controls>';
            echo '<source src="../../uploads/' . \Helpers\Data::html($data['fileinfo']['server_name']) . '">';
            echo '</audio>';

          } else {
            echo '<img class="img-responsive center-block" src="' . \Helpers\Url::templatePath() . '/images/no_preview.jpg">';
          }

           ?>
          <ul class="file-properties">
            <li><i class="fa fa-user"></i><?= \Helpers\Data::html($data['fileinfo']['user_name']); ?></li>
            <li><i class="fa fa-hdd-o"></i><?= \Helpers\Data::html(\Helpers\Document::formatBytes($data['fileinfo']['file_size'], '1')); ?></li>
            <li><i class="fa fa-calendar"></i><?= \Helpers\Data::html(date('d.m.Y', $data['fileinfo']['upload_date'])); ?></li>
            <li><i class="fa fa-download"></i>Всего загрузок: <?= \Helpers\Data::html($data['fileinfo']['total_downloads']); ?></li>
            <li><i class="fa fa-exclamation-triangle"></i><a class="make-appeal" href="">Пожаловаться</a></li>

            <?php

            if($_SESSION['rf_user'] === 'admin') {
              echo '<li><i class="fa fa-times"></i><a class="delete-file" href="?fileaction=delete">Удалить</a></li>';
            }

             ?>

            <div class="appeal">
              <form class="appeal-form" method="POST" action="/files/appeal">
                <p>Пожалуйста, выберите тип нарушения:</p>
                <select name="appeal-type">
                  <option value="Спам">Спам</option>
                  <option value="Оскорбление">Оскорбление</option>
                  <option value="Материал для взрослых">Материал для взрослых</option>
                  <option value="Пропаганда наркотиков">Пропаганда наркотиков</option>
                  <option value="Детская порнография">Детская порнография</option>
                  <option value="Насилие/экстримизм">Насилие/экстримизм</option>
                </select>
                <p>Дополнительное пояснение (опционально):</p>
                <textarea class="text-input" name="appealtext" maxlength="512"></textarea>
                <input type="hidden" name="fileid" value="<?= \Helpers\Data::html($data['fileinfo']['id']); ?>">
              </form>
            </div>
          </ul>
          <a href="/download?id=<?= \Helpers\Data::html($data['fileinfo']['id']); ?>" class="btn btn-warning center-block download-button">Скачать</a>

          <?php

          if(isset($data['imageinfo'])) {
            require 'imageinfo.php';
          } elseif (isset($data['mediainfo'])) {
            require 'mediainfo.php';
          }

           ?>

          <div class="comments">
          <h4 class="comments-header">Комментарии</h4>

          <?php

          if(empty($data['comments'])) {

            echo '<div class="alert alert-warning no-comments">Комментариев пока нет!</div>';

          } else {

            foreach($data['comments'] as $comment) {
              echo '<div class="panel panel-warning">';

              echo '<div class="panel-heading">';
              echo '<ul>';
              echo ' <li><i class="fa fa-user"></i> ' . \Helpers\Data::html($comment['user_name']) . '</li>';
              echo ' <li><i class="fa fa-calendar"></i> ' . \Helpers\Data::html(date('d.m.Y', $comment['date'])) . '</li>';
              echo ' <li><i class="fa fa-clock-o"></i> ' . \Helpers\Data::html(date('H:i',$comment['date'])) . '</li>';

              if($_SESSION['rf_user'] === 'admin') {
                echo '<a class="delete-comment" href="?commentaction=delete&commentid=' . $comment['id'] . '" style="float:right;"><i class="fa fa-times"></i></a>';
              }
              
              echo '</ul>';
              echo '</div>';

              echo '<div class="panel-body">';
              echo \Helpers\Data::html($comment['comment']);
              echo '</div>';

              echo '</div>';
            }

          }

           ?>

          <a class="comments-toggle">Добавить комментарий</a>
          <div class="add-comment">
            <form class="comment-form" method="POST" action="/files/<?= \Helpers\Data::html($data['fileinfo']['id']); ?>">
                <textarea class="text-input" name="comment" rows="3" maxlength="512" required></textarea>
                <input type="hidden" name="token" value="<?= $data['token']; ?>">
                <div class="g-recaptcha" data-sitekey="6LdLrwgTAAAAAO8emgOF133VweywoK4bGcdA2UMz"></div>
                <button type="submit" class="btn btn-warning add-comment-button">Отправить</button>
              </div>
            </form>
          </div>
        </div>