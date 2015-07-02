<main>
  <div class="container">
    <div class="register-block main-block center-block clearfix">
      <h3>Регистрация аккаунта</h3>
      <form class="register-form" method="POST" action="/register">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" class="text-input" id="username" maxlength="32" required>
        <label for="email">Email:</label>
        <input type="email" name="email" class="text-input" id="email" required>
        <label for="pass">Пароль:</label>
        <input type="password" name="password" class="text-input" id="pass" maxlength="32" required>
        <label for="pass-confirm">Подтвердите пароль:</label>
        <input type="password" name="passconfirm" class="text-input" id="pass-confirm" maxlength="32" required>
        <span class="response"></span>
        <button type="submit" class="btn btn-warning">Регистрация</button>
      </form>
    </div>