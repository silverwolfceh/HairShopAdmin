<?php
	@session_start();
	if(!isset($_SESSION['uname']) || $_SESSION['uname'] != 'admin')
		header("Location: index.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript"  src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<link rel="stylesheet" type="text/css" href="css/admin.css" media="all" />
<script type="text/javascript" src="sdmenu/sdmenu.js"></script>
<title>
Administrator Area
</title>
<script>
var mhd = -1;
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
			var myWindow = window.open("", "myWindow", "width=530, height=500,resizable=no"); 
			myWindow.document.write(reponse);
		}
	});
}
function thongke()
{
	var m = document.getElementById("month").value;
	var y = document.getElementById("year").value;
	jQuery.ajax({
		type: "POST",
		url: "process.php",
		data: {'optcode': 'filter', 'm' : m, 'y' : y},
		async:false,
		success: function(reponse) {
			document.getElementById("result").innerHTML = reponse;
		}
	});
}
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
<div id="topmenu" style="position:relative;left:0px;top:0px;width:1024px">
	<a href='index.php'><img src='http://png-2.findicons.com/files/icons/1261/sticker_system/128/home.png' width=50px /></a>
</div>
<div id="leftmenu" style="position:absolute;left:0px;top:50px;width:200px">
<div id="menu-title" class="menu-title">MENU QUẢN LÝ</div>
<div id="mymenu" name="mymenu" class="sdmenu">
	<!--
	 <div>
        <span>Quản Lý Trang</span>
        <a href="?arg1=cau-hinh">Cấu hình site</a>
     </div>
    -->
     <div>
        <span>Quản Lý Nhân Viên</span>
        <a href="?arg1=nhan-vien&arg2=xem">Danh sách nhân viên</a>
        <a href="?arg1=nhan-vien&arg2=them">Thêm nhân viên</a>
     </div>
      <div>
        <span>Quản Lý Mua Vật Liệu</span>
        <a href="?arg1=chi&arg2=them">Thêm khoản chi</a>
     </div>
	 <div>
        <span>Quản Lý Dịch Vụ</span>
        <a href="?arg1=dich-vu&arg2=xem">Dịch vụ hiện có</a>
        <a href="?arg1=dich-vu&arg2=them">Thêm dịch vụ mới</a>
     </div>
      <div>
        <span>Thống kê</span>
        <a href="?arg1=dsthu&arg2=xem">Theo danh sách thu</a>
        <a href="?arg1=dschi&arg2=xem">Theo danh sách chi</a>
        <a href="?arg1=thongke&arg2=thang">Thu theo tháng</a>
        <a href="?arg1=thongke&arg2=tuan">Thu theo tuần</a>
        <a href="?arg1=thongke&arg2=ngay">Thu theo ngày</a>
      </div>
    </div>
</div>
</div>
<div id="content" style="position:absolute;left:260px;top:80px;width:1024px;float:left;text-align:left;border-style:solid;border-width:2px;min-height:750px">
<?php
	if(isset($_GET['arg1']))
	{
		switch ($_GET['arg1'])
		{
			case 'cau-hinh':
				break;
			case 'dich-vu':
				switch ($_GET['arg2'])
				{
					case 'them':
						if(!isset($_GET['action']) || $_GET['action'] != 'do' )
						{
							echo "<center>";
							echo "<h3> Thêm dịch vụ mới </h3>";
							echo "<form method='post' action='admin.php?arg1=dich-vu&arg2=them&action=do'>";
							echo "<input type='text' name='dichvu' id='dichvu' placeholder='Tên dịch vụ' /><br /><br />";
							echo "<input type='text' name='dongia' id='dongia' placeholder='Đơn giá' /><br /><br />";
							echo "<input type='text' name='chietkhau' id='chietkhau' placeholder='Chia admin' /><br />";
							echo "<input type='hidden' name='back' id='back' value='admin.php?arg1=dich-vu' /><br />";
							echo "<input type='submit' value='Lưu' /></form>";
							echo "</center>";
						}
						else if(isset($_GET['action']) && $_GET['action'] == "do")
						{
							require_once('item.php');
							$obj = new item();
							$obj->add($_POST['dichvu'],$_POST['dongia'],$_POST['chietkhau']);
							header("Location: admin.php?arg1=dich-vu&arg2=xem");
						}
						break;
					case 'xem':
						require_once('item.php');
						$rs = item::loadAllItem();
						if(!$rs) 
							echo "<h4> Chưa có dịch vụ nào. <a href='admin.php?arg1=dich-vu&arg2=them'>Thêm ngay</a></h4>";

						$ouput = "<table width=50% border=1>";
						$ouput .= "<tr><th> Mã số </th><th > Tên dịch vụ </th><th> Giá </th><th> Chiết khấu </th><th> Công cụ </th></tr>";
						while ($r = mysql_fetch_array($rs))
						{
							$ouput .= "<tr>";
							$ouput .= "<td align='center'>".$r['masp']."</td>";
							$ouput .= "<td align='left'>".$r['tensp']."</td>";
							$ouput .= "<td align='right'>".$r['giamacdinh']."</td>";
							$ouput .= "<td align='right'>".$r['chietkhau']."</td>";
							$ouput .= "<td align='center'><a href='admin.php?arg1=dich-vu&arg2=xoa&masp=".$r['masp']."'>Xóa</a></td>";
							$ouput .= "</tr>";
						}
						echo $ouput;
						break;
					case 'xoa':
						require_once('item.php');
						$obj = new item($_GET['masp']);
						$obj->delete();
						header("Location: admin.php?arg1=dich-vu&arg2=xem");

					default:
						header("Location: admin.php?arg1=dich-vu&arg2=xem");
						break;
				}
				break;
			case 'dsthu':
				switch ($_GET['arg2'])
				{
					case 'xem':
						require_once("hoadon.php");
						$rs = hoadon::loadAll();
						if(!$rs)
							die();
						$output  = "<table border=1 width=100%>";
						$output .= "<tr><th>Mã hóa đơn</th><th> Ngày lập </th><th> Nhân Viên </th><th> Trị giá </th><th> Chiết khấu </th><th> Công cụ </th></tr>";
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
						break;
					case 'phanloai':
						echo "<h3>Chọn 1 tháng, năm và nhấn 'GO' </h3>";
						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="month" id="month">';
						for($i=1; $i<=12; $i++)
						{
							if($i <10)
								echo '<option value="0'.$i.'">0'.$i.'</option>';
							else
								echo '<option value="'.$i.'">'.$i.'</option>';
						}
						echo '</select>';
						echo '&nbsp;&nbsp;&nbsp;<select name="year" id="year">';
						for($i=2014; $i<=2020; $i++)
						{
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						echo '</select>&nbsp;&nbsp;&nbsp';
						echo '<input type="button" value="GO!" onclick="thongke()">';
						echo '<div id="result"></div>';
						break;
				}
				break;
			default:
				# code...
				break;
		}
	}
?>
</div>
</div></div>
</center>
</body>
</html>