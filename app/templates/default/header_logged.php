<div class="menu-user">
	<div class="logout-button">
		<a href="/user/logout" class="btn btn-warning">Выход</a>
	</div>
	<div class="user-name">
		<a href="/user/<?= \Helpers\Session::get('user_id'); ?>">
			<i class="fa fa-user"></i>
			<?= \Helpers\Data::html(\Helpers\Session::get('user')); ?>
		</a>
	</div>
</div>