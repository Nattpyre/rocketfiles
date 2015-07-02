<h4>Результаты поиска</h4>
<form method="GET" action="/files">
      <button type="submit" class="btn btn-warning">Поиск</button>
      <input type="search" name="search" class="text-input" maxlength="32" required>
</form>

      <?php

      if(!empty($data['search'])) {

            echo '<table class="table table-bordered table-condensed table-hover">';
            echo '<tr>';
            echo '<th>Имя файла</th>';
            echo '<th>Автор</th>';
            echo '<th>Размер</th>';
            echo '<th>Дата загрузки</th>';
            echo '</tr>';

            foreach($data['search'] as $file) {
            echo '<tr>';
            echo '<td><a href="/files/' . \Helpers\Data::html($file['id']) . '">' . \Helpers\Data::html($file['file_name']) . '</a></td>';
            echo '<td>' . \Helpers\Data::html($file['user_name']) . '</td>';
            echo '<td>' . \Helpers\Data::html(\Helpers\Document::formatBytes($file['file_size'], '1')) . '</td>';
            echo '<td>' . \Helpers\Data::html(date('d.m.Y', $file['upload_date'])) . '</td>';
            echo '</tr>';
            }

            echo '</table>';
      } else {
            echo '<div class="alert alert-warning empty-result">По вашему запросу ничего не найдено!</div>';
      }

       ?>