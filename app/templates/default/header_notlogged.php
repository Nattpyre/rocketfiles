<form class="menu-login" method="POST" action="/login">
	<div class="icon">
		<label for="login"><i class="fa fa-user"></i></label>
	</div>
	<div class="user-login">
		<input type="text" id="login" name="userlogin" placeholder="Логин" maxlength="32" required>
	</div>
	<div class="icon">
		<label for="password"><i class="fa fa-lock"></i></label>
	</div>
	<div class="user-password"> 
		<input type="password" id="password" name="userpassword" placeholder="Пароль" maxlength="32" required>
	</div>
	<div class="login-buttons">
		<input type="hidden" name="token" value="<?= \Helpers\Data::html($data['token']); ?>">
		<button type="submit" class="btn btn-warning header-login-button">Вход</button>
		<a href="/register" class="btn btn-warning">Регистрация</a>
	</div>
</form>