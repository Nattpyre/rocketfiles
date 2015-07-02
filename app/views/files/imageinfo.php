<table class="table table-bordered table-condensed table-hover">

  <?php 

  //Таблица с информацией об изображении

  if(!empty($data['imageinfo'])) {
    echo '<tr>';
    echo '<th colspan="2">Изображение</th>';
    echo '</tr>';
  }

  if(!empty($data['imageinfo']['mime'])) {
    echo '<tr>';
    echo '<td>Формат:</td>';
    
    switch ($data['imageinfo']['mime']) {
      case 'image/jpeg':
        echo '<td>JPEG (Joint Photographic Experts Group JFIF format)</td>';
        break;
      case 'image/gif':
        echo '<td>GIF (Graphics Interchange Format)</td>';
        break;
      case 'image/png':
        echo '<td>PNG (Portable Network Graphics)</td>';
        break;
      case 'image/bmp':
        echo '<td>BMP (Bitmap Picture)</td>';
        break;
      case 'image/tiff':
        echo '<td>TIFF (Tagged Image File Format)</td>';
        break;
  }

    echo '</tr>';
  }

  if(!empty($data['imageinfo'][0]) && !empty($data['imageinfo'][1])) {
    echo '<tr>';
    echo '<td>Размер:</td>';
    echo '<td>' . \Helpers\Data::html($data['imageinfo'][0]) . 'x' . \Helpers\Data::html($data['imageinfo'][1]) . '</td>';
    echo '</tr>';
  }

  if(!empty($data['imageinfo']['bits'])) {
    echo '<tr>';
    echo '<td>Глубина цвета:</td>';
    echo '<td>' . \Helpers\Data::html($data['imageinfo']['bits']) . '-bit' . '</td>';
    echo '</tr>';
  }

  if(isset($data['imageinfo']['channels'])) {
    echo '<tr>';
    echo '<td>Цветовое пространство:</td>';
    $channels = $data['imageinfo']['channels'] == '3' ? 'RGB' : 'CMYK';
    echo '<td>' . \Helpers\Data::html($channels) . '</td> ';
    echo '</tr>';
  }

  if(isset($data['imageinfo']['saved'])) {
    echo '<tr>';
    echo '<td>Дата сохранения:</td>';
    echo '<td>' . date('d.m.Y', \Helpers\Data::html($data['imageinfo']['saved']['FileDateTime'])) . '</td>';
    echo '</tr>';
  }

  ?>

</table>