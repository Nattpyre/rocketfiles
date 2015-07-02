<table class="table table-bordered table-condensed table-hover">
      <tr>
            <th>Имя файла</th>
            <th>Размер</th>
            <th>Дата загрузки</th>
            <th></th>
      </tr>
      <?php

      foreach($data['files'] as $file) {
            echo '<tr>';
            echo '<td><a href="/files/' . \Helpers\Data::html($file['id']) . '">' . \Helpers\Data::html($file['file_name']) . '</a></td>';
            echo '<td>' . \Helpers\Data::html(\Helpers\Document::formatBytes($file['file_size'], '1')) . '</td>';
            echo '<td>' . \Helpers\Data::html(date('d.m.Y', $file['upload_date'])) . '</td>';
            echo '<td><a class="delete-file" href="/user/' . \Helpers\Data::html($_SESSION['rf_user_id']) . '?fileid=' . \Helpers\Data::html($file['id']) . '&fileaction=delete' . '"><i class="fa fa-trash-o"></i></a></td>';
            echo '</tr>';
      }

       ?>
</table>