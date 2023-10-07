<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/images/icon.ico">
<?php 
foreach($data['css_files'] as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
</head>
<body>
	<div>
		<a href="<?= base_url() ?>admin/usuarios">Usuários</a> |
		<a href="<?= base_url() ?>admin/perfis">Perfis</a> |
		<a href="<?= base_url() ?>admin/seguidores">Seguidores</a> |
		<a href="<?= base_url() ?>admin/publicacoes">Publicações</a> | 
		<a href="<?= base_url() ?>admin/comentarios">Comentários</a>
	</div>
	<div style='height:20px;'></div>  
    <div style="padding: 10px">
		<?php echo $data['output']; ?>
    </div>
    <?php foreach($data['js_files'] as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

	<a href="<?= base_url() ?>pages/feed">Voltar para o feed</a>
</body>
</html>
