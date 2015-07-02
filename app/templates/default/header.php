<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="user-scalable=no">

	<title><?= \Helpers\Data::html($data['title'].' - '.SITETITLE); ?></title>

	<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?= \Helpers\Url::templatePath() . 'images/favicon.ico' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'vendor/bootstrap/bootstrap.min.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'css/fonts.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'css/style.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'css/media.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'vendor/jquery-ui-1.11.4/jquery-ui.min.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'vendor/jquery-ui-1.11.4/jquery-ui.structure.min.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'vendor/jquery-ui-1.11.4/jquery-ui.theme.min.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'vendor/font-awesome-4.3.0/css/font-awesome.min.css' ?>">
	<link rel="stylesheet" href="<?= \Helpers\Url::templatePath() . 'vendor/formvalidation/formValidation.min.css' ?>">
	
	<script src="<?= \Helpers\Url::templatePath() . 'vendor/jquery/jquery-2.1.4.min.js' ?>"></script>
	<script src="<?= \Helpers\Url::templatePath() . 'vendor/bootstrap/bootstrap.min.js' ?>"></script>
	<script src="<?= \Helpers\Url::templatePath() . 'vendor/jquery-ui-1.11.4/jquery-ui.min.js' ?>"></script>
	<script src="<?= \Helpers\Url::templatePath() . 'vendor/jquery-ui-1.11.4/jquery.fileupload.js' ?>"></script>
	<script src="<?= \Helpers\Url::templatePath() . 'vendor/formvalidation/formValidation.min.js' ?>"></script>
	<script src="<?= \Helpers\Url::templatePath() . 'vendor/formvalidation/framework/bootstrap.min.js' ?>"></script>
	<script src="<?= \Helpers\Url::templatePath() . 'js/main.js' ?>"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
	<header>
		<nav class="header-menu">
			<ul class="menu-list">
				<li>
					<a href="/">Главная</a>
				</li>
				<li>
					<a href="/files">Файлы</a>
				</li>
				<li>
					<a href="/about">Об авторе</a>
				</li>
				<li>
					<a href="/faq">FAQ</a>
				</li>
			</ul>

			<?php

			if(!empty($_SESSION['rf_user'])) {
				require __DIR__ . '/header_logged.php';
			} else {
				require __DIR__ . '/header_notlogged.php';
			}
		?>

		</nav>
		<div class="logo center-block">
			<a href="/">
				<img src="<?= \Helpers\Url::templatePath() . 'images/logo.png' ?>" alt="logo">
			</a>
		</div>
		<div class="dialog"></div>
	</header>