<?php @session_start(); ?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript"  src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/admin.js"></script>
	<link rel="stylesheet" type="text/css" href="css/admin.css" media="all" />
	<script type="text/javascript" src="sdmenu/sdmenu.js"></script>
	<script type="text/javascript">
	var ids = "";
	var prices = "";
	var mhd = -1;
	function adddata()
	{
		var x = document.getElementById("items").value;
		ids = ids + ":" + x;
		var slidx = document.getElementById("items").selectedIndex;
		x = document.getElementById("items")[slidx].innerHTML;
		var y = document.getElementById("actual").value;
		var z = x.split(":::"); 
		var x1 = z[0];
		var x2 = z[1];

		if(y != "")
		{
			alert(y);
			x2 = y;
		}
		prices = prices + ":" + x2;
		var k = document.getElementById("addsp").innerHTML;
		k += x1 + "..............." + x2 + "<br />";

		document.getElementById("addsp").innerHTML = k;

	}
	function savedata()
	{
		var kh = document.getElementById("khachhang").value;
		var ct = document.getElementById("addsp").innerHTML;
		document.getElementById("save").disabled = true;
		jQuery.ajax({
    		type: "POST",
    		url: "process.php",
    		data: {'optcode': 'luu-hoa-don', 'khachhang' : kh, 'prices' : prices,'ids' : ids},
    		async:false,
    		success: function(reponse) {
    			document.getElementById("print").disabled = false;
    			alert("Thành công!");
    			mahd = reponse;
    		}
    	});
	}
	function login()
	{
		var us = document.getElementById("username").value;
		var pas = document.getElementById("password").value;
		jQuery.ajax({
    		type: "POST",
    		url: "process.php",
    		data: {'optcode': 'login', 'username' : us, 'password' : pas},
    		async:false,
    		success: function(reponse) {
    			if(reponse.indexOf("OK") != -1)
    			{
    				document.getElementById("loginstatus").innerHTML = "Login thành công";
    				document.location.href = "index.php";
    			}
    			else
    			{
    				document.getElementById("loginstatus").innerHTML = "Login thất bại";
    			}
    				
    			
    		}
    	});
    	return false;
	}
	function setandprint(ma)
	{
		mahd = ma;
		printdata();
	}
	function printdata()
	{
		jQuery.ajax({
    		type: "POST",
    		url: "process.php",
    		data: {'optcode': 'in-hoa-don', 'mahd' : mahd},
    		async:false,
    		success: function(reponse) {
    			var myWindow = window.open("index.php?in-hoa-don", "myWindow", "width=530, height=500,resizable=no"); 
    			myWindow.document.write(reponse);
    		}
    	});
	}
	</script>
	<title>
	 Quản lý hớt tóc
	</title>
	<script>
	var myMenu;
		window.onload = function() {
			myMenu = new SDMenu("mymenu");
			myMenu.init();
		};
	</script>
	</head>
	<body >
		<center>
<div style="border-style:solid;border-width:1px;">
<div id="container" >
<div id="topmenu" style="position:relative;left:0px;top:10px;width:1024px">
</div>
<div id="leftmenu" style="position:absolute;left:0px;top:50px;width:200px">
<div id="menu-title" class="menu-title">Công Cụ</div>
<div id="mymenu" name="mymenu" class="sdmenu">
	 <div>
<?php
	if(isset($_SESSION['uname']))
	{
?>
        <span>Hóa đơn</span>
        <a href="?arg1=them-hoa-don">Tạo hóa đơn</a>   
        <a href="?arg1=xem-hoa-don">Danh sách hóa đơn</a>

        <span>Người dùng <?php echo $_SESSION['uname']; ?></span>
        <a href="?arg1=log-out">Logout</a>   
        <a href="admin.php">Admin</a>   
     
<?php
	}
	else
	{
?>
	<span>Please login</span>
<?php
	}
?>
</div>
</div>
</div>
<div id="content" style="position:absolute;left:260px;top:80px;width:1024px;float:left;text-align:left;border-style:solid;border-width:2px;min-height:750px">
		<?php
			if(isset($_GET['arg1']) && $_GET['arg1'] == 'them-hoa-don' )
			{
		?>
		<center><h3> Phiếu Thanh Toán </h3></center>
		<form method='post' action=''>
		<center>
			<table with=50% border=0 style='color:blue;font-size:20px'>
				<tr><td>Ngày tạo phiếu: </td><td> <?php echo date('d-m-Y'); ?> </td></tr>
				<tr><td>Nhân viên: </td><td> <?php echo $_SESSION['uname']; ?> </td></tr>
			</table>
			<center><h4> Dịch vụ sử dụng </h4></center>
			<?php
				require_once('item.php');
				$rs = item::loadAllItem();
				echo "<select name='items' id='items'>";
				while($r = mysql_fetch_array($rs))
				{
					echo "<option value='".$r['masp']."'>".$r['tensp'].":::".$r['giamacdinh']."</option>";
				}
				echo "</select>";
				echo "<input type='text' name='actual' id='actual' placeholder='Giá thực' />";
				echo "<input type='button' name='add' id='add' value='Add' onclick='adddata()' />";
			?>
			<div id='addsp' style='align:right'>
			</div>
			<input type='button' name='save' id='save' value='Save' onclick='savedata()' />
			<input type='hidden' name='khachhang' id='khachhang' value='' />
			<input type='button' name='print' id='print' value='Print' onclick='printdata()' disabled />
		</center>
		<?php		
			}
			else if(isset($_GET['arg1']) && $_GET['arg1'] == 'xem-hoa-don')
			{
				require_once("hoadon.php");
				$rs = hoadon::loadAll($_SESSION['uname']);
				if(!$rs)
					die();
				if(mysql_num_rows($rs) == 0)
				{
					echo "<font color='red'><strong>Bạn chưa có hóa đơn nào được tạo..<a href='index.php?arg1=them-hoa-don' style='text-decoration:none;'>Tạo một hóa đơn ngay</strong></font>";
					die();
				}
				$output  = "<table border=1 width=80%>";
				$output .= "<tr><th>Mã hóa đơn</th><th> Ngày lập </th><th> Người lập </th><th> Trị giá </th><th> Chiết khấu </th><th> Công cụ </th></tr>";
				while($r = mysql_fetch_array($rs))
				{
					$output .= "<tr>";
					$output .= "<td align='center'>".$r['mahd']."</td>";
					$output .= "<td align='center'>  ".$r['ngaylap']." </td>";
					$output .= "<td align='center'>  ".$r['nguoilap']." </td>";
					$output .= "<td align='center'> ".$r['total']." </td>";
					$output .= "<td align='center'> ".$r['chietkhau']." </td>";
					$output .= '<td align="center"> <a href="#"" onclick=\'setandprint("'.$r['mahd'].'");\'>View </a> </td>';
					$output .= "</tr>";
				}
				$output .= "</table>";
				echo $output;
			}
			else if(isset($_GET['arg1']) && $_GET['arg1'] == 'log-out')
			{
				unset($_SESSION['uname']);
?>
				<script type="text/javascript">document.location.href="index.php";</script>
<?php
			}


			if(!isset($_SESSION['uname']) || $_SESSION['uname'] == "")
			{
		?>
			<center>
			<h3> Login to continue </h3>
			<input type='text' name='username' id='username' placeholder='Enter username' /> <br />
			<input type='password' name='password' id='password' placeholder='Enter password' /> <br />
			<input type='hidden' name='optcode' id='optcode' value='login' />
			<input type='submit' value='Login' onclick="login()" />
			<div id="loginstatus"></div>
			</center>
		<?php		
			}
		?>
</div>
	</div></div>
</center>
</body>
</html>