<main>
  <div class="container">
    <div class="main-block forgot-block center-block clearfix">
      <h3>Восстановление пароля</h3>
      <form class="forgot-email" method="POST" action="/login/forgot">
        <label for="email">Email:</label>
        <input type="email" name="email" class="text-input" id="email" required>
        <span class="response"></span>
        <button type="submit" class="btn btn-warning">Отправить</button>
      </form>
    </div>
