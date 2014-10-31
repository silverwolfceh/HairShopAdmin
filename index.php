<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	if(!isset($_GET['arg1']))
		header("Location: index.php?arg1=xem-hoa-don");
	@session_start();
	require_once("template.php");
?>