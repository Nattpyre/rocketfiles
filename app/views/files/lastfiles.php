<h4>Последние файлы</h4>
<form method="GET" action="/files">
      <button type="submit" class="btn btn-warning">Поиск</button>
      <input type="search" name="search" class="text-input" maxlength="32" required>
</form>
<table class="table table-bordered table-condensed table-hover">
      <tr>
            <th>Имя файла</th>
            <th>Автор</th>
            <th>Размер</th>
            <th>Дата загрузки</th>
      </tr>

      <?php

      foreach($data['lastfiles'] as $file) {
            echo '<tr>';
            echo '<td><a href="/files/' . \Helpers\Data::html($file['id']) . '">' . \Helpers\Data::html($file['file_name']) . '</a></td>';
            echo '<td>' . \Helpers\Data::html($file['user_name']) . '</td>';
            echo '<td>' . \Helpers\Data::html(\Helpers\Document::formatBytes($file['file_size'], '1')) . '</td>';
            echo '<td>' . \Helpers\Data::html(date('d.m.Y', $file['upload_date'])) . '</td>';
            echo '</tr>';
      }

       ?>
       
</table>