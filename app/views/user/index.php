<main>
      <div class="container">
          <div class="user-block main-block center-block clearfix">
          <h3>Аккаунт</h3>
          <p>На этой странице вы можете изменить ваш пароль и/или Email, удалить аккаунт,
           а так же просмотреть список всех загруженных файлов.
          </p>
          <h4>Настройки аккаунта</h4>
          <form class="refresh-info" method="POST" action="/user/<?= \Helpers\Data::html($data['result']['id']); ?>">
              <label>Логин:</label>
                <p class="form-control-static"><?= \Helpers\Data::html($data['result']['login']); ?></p>
                <input type="hidden" name="user-id" value="<?= \Helpers\Data::html($data['result']['id']); ?>">
              <label for="new-email">Новый Email:</label>
                <input type="hidden" name="old-email" value="<?= \Helpers\Data::html($data['result']['email']); ?>">
                <input type="email" class="text-input" name="new-email" id="new-email" value="<?= \Helpers\Data::html($data['result']['email']); ?>" required>
              <label for="new-password">Нов. пароль:</label>
                <input type="password" class="text-input" name="new-password" id="new-password" value="" maxlength="32" required>
              <label for="confirm-new-password">Еще раз:</label>
                <input type="password" class="text-input" name="confirm-new-password" id="confirm-new-password" value="" maxlength="32" required>
                <button class="btn btn-warning refresh">Обновить</button>
          </form>
          <h4>Загруженные файлы</h4>

          <?php

          if(empty($data['files'])) {
            echo '<div class="alert alert-warning">У вас нет загруженных файлов!</div>';
          } else {
            require_once 'files_table.php';
          }

           ?>

          <h4>Удаление аккаунта</h4>
          <div class="alert alert-danger" role="alert">Внимание! Удаление аккаунта невозможно отменить,
           так что делайте это только если полностью уверены в своем решении.
          </div>
          <form class="delete-user-form" method="GET" action="/user/<?= \Helpers\Data::html($data['result']['id']); ?>">
            <input type="hidden" name="useraction" value="delete">
            <button type="submit" class="btn btn-warning delete-user">Удалить аккаунт</button>
          </form>
          </div>