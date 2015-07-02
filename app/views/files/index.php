<main>
  <div class="container">
    <div class="main-block files-block center-block clearfix">
      <h3>Файлы</h3>
      <p>
        Здесь вы можете посмотреть список последних загруженных файлов, а так же произвести поиск по всем файлам.
      </p>

      <?php

      if(array_key_exists('search', $_GET)) {
        require_once 'search.php';
            
        echo $data['searchPageLinks'];
      } else {
        require_once 'lastfiles.php';

        echo $data['pageLinks'];
      }

      ?>

    </div>