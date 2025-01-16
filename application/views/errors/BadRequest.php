<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ERROR</title>
</head>
<body>
	<h1>400 - BadRequest</h1>
	<p>The service that you try to use, generate a error</p>
	<?php if (isset($error) && $error != null) : ?>
		<p><?= $error ?></p>
	<?php endif; ?>
</body>
</html>