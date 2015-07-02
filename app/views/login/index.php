<main>
  <div class="container">
    <div class="login-block main-block center-block clearfix">
      <h3>Вход</h3>
      <form class="login-form" method="POST" action="/login">
        <label for="username">Логин:</label>
        <input type="text" name="userlogin" class="text-input" id="username" maxlength="32" required>
        <label for="pass">Пароль:</label>
        <input type="password" name="userpassword" class="text-input" id="pass" maxlength="32" required>
        <span class="response"></span>
        <input type="hidden" name="token" value="<?= $data['token']; ?>">
        <a href="/login/forgot">Забыли пароль?</a>
        <label  class="checkbox">
          <input type="checkbox" name="checkbox">Запомнить меня
        </label>
        <button type="submit" class="btn btn-warning">Войти</button>
      </form>
    </div>