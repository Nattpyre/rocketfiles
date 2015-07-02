<!DOCTYPE html>
<html>
<head>
	<title>404</title>
</head>
<body>

	<h1>404</h1>

	<?= \Helpers\Data::html($data['error']); ?>

	<hr/>

	<h3>Страница, которую вы пытались открыть не найдена.</h3>
	<p>
		Это могло случиться потому, что она была удалена, переименована или просто временно недоступна.
	</p>
	<h3>Решение проблемы</h3>

	<ul>
		<li>Если вы набирали адрес вручную, то проверьте введеный вами адрес.</li>
		<li>
			Вернитесь на главную страницу.
		</li>
	</ul>

</body>
</html>