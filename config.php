<?php
	$tentiem = 'Beauty Salon Tân Bình';
	$diachi  = '123, Trường Chinh, Tân Bình, Hồ Chí Minh';
	$dienthoai = '0987654321';
    $dbhost='localhost';
    $dbuser='root';
    $dbpass='';
    $dbname='hottoc';
    $link=@mysql_connect($dbhost,$dbuser,$dbpass) or die('Couldn\"t connect to database');
    $db=mysql_select_db($dbname,$link);
    mysql_query("SET NAMES 'UTF8'");
?>