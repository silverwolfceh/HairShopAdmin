<?php
	$tentiem = 'Hair Salon Quan';
	$diachi  = '123, Nguyễn Văn Quá, P.1, Q. 12';
	$dienthoai = '0987.654.321';
    $dbhost='localhost';
    $dbuser='root';
    $dbpass='';
    $dbname='hottoc';
    $resetpassword = '123456';
    $link=@mysql_connect($dbhost,$dbuser,$dbpass) or die('Couldn\"t connect to database');
    $db=mysql_select_db($dbname,$link);
    mysql_query("SET NAMES 'UTF8'");
?>