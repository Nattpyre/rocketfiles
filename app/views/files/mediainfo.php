<table class="table table-bordered table-condensed table-hover">

  <?php 

  //Таблица с информацией о видео

  if(!empty($data['mediainfo']) && !empty($data['mediainfo']['video'])) {
    echo '<tr>';
    echo '<th colspan="2">Видео</th>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['video']['resolution_x']) && !empty($data['mediainfo']['video']['resolution_y'])) {
    echo '<tr>';
    echo '<td>Разрешение:</td>';
    echo '<td>' . \Helpers\Data::html($data['mediainfo']['video']['resolution_x']) . 'x' . \Helpers\Data::html($data['mediainfo']['video']['resolution_y']) . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['playtime_string']) && $fileType == 'video') {
    echo '<tr>';
    echo '<td>Длительность:</td>';
    echo '<td>' . \Helpers\Data::html($data['mediainfo']['playtime_string']) . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['video']['dataformat'])) {
    echo '<tr>';
    echo '<td>Кодек:</td>';
    echo '<td>' . \Helpers\Data::html(strtoupper($data['mediainfo']['video']['dataformat'])) . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['video']['frame_rate'])) {
    echo '<tr>';
    echo '<td>Фреймрейт:</td>';
    echo '<td>' . \Helpers\Data::html($data['mediainfo']['video']['frame_rate']) . ' fps' . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['video']['bitrate'])) {
    echo '<tr>';
    echo '<td>Битрейт:</td>';
    echo '<td>' . \Helpers\Data::html(round($data['mediainfo']['video']['bitrate'] / 1024)) . ' Kbps' . '</td>';
    echo '</tr>';
  }

  //Таблица с информацией о аудио

  if(!empty($data['mediainfo']) && !empty($data['mediainfo']['audio'])) {
    echo '<tr>';
    echo '<th colspan="2">Аудио</th>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['playtime_string']) && $fileType == 'audio') {
    echo '<tr>';
    echo '<td>Длительность:</td>';
    echo '<td>' . \Helpers\Data::html($data['mediainfo']['playtime_string']) . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['audio']['dataformat'])) {
    echo '<tr>';
    echo '<td>Кодек:</td>';
    echo '<td>' . \Helpers\Data::html(ucfirst($data['mediainfo']['audio']['dataformat'])) . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['audio']['bitrate'])) {
    echo '<tr>';
    echo '<td>Битрейт:</td>';
    echo '<td>' . \Helpers\Data::html(round($data['mediainfo']['audio']['bitrate'] / 1000)) . ' Kbps' . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['audio']['channels'])) {
    echo '<tr>';
    echo '<td>Количество каналов:</td>';
    echo '<td>' . \Helpers\Data::html($data['mediainfo']['audio']['channels']) . '</td>';
    echo '</tr>';
  }

  if(!empty($data['mediainfo']['audio']['sample_rate'])) {
    echo '<tr>';
    echo '<td>Частота дискретизации:</td>';
    echo '<td>' . \Helpers\Data::html(round($data['mediainfo']['audio']['sample_rate'] / 1000, '1')) . ' KHz' . '</td>';
    echo '</tr>';
  }

  ?>

</table>