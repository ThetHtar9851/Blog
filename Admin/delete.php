<?php
	require '../config/config.php';
	$stat = $pdo->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
	$stat->execute();

	header('Location: index.php');
?>
