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
		data: {'optcode': 'in-hoa-don', 'mahd' : mahd, 'in': 0},
		async:false,
		success: function(reponse) {
			var myWindow = window.open("", "Print Window", "width=268, height=500,resizable=yes"); 
			myWindow.document.write("<div style='width:260px'>");
			myWindow.document.write(reponse);
			myWindow.document.write("</div>");
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
	<a href='index.php'><img src='home.png' width=50px /></a>
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
        <a href="?arg1=nhan-vien&arg2=xemnghiviec">Danh sách nghỉ việc</a>
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
        <a href="?arg1=thongke&arg2=all">Theo danh sách thu</a>
        <a href="?arg1=chi&arg2=xem">Theo danh sách chi</a>
        <a href="?arg1=thongke&arg2=nam">Thống kê theo năm</a>
        <a href="?arg1=thongke&arg2=thang">Thống kê theo tháng</a>
        <a href="?arg1=thongke&arg2=ngay">Thống kê theo ngày</a>
        <a href="?arg1=thongke&arg2=khoan">Thống kê từ....đến...</a>
        <a href="?arg1=thongke&arg2=nhanvien">Thống kê theo nhân viên</a>
      </div>
    </div>
</div>
</div>
<div id="content" style="position:absolute;left:260px;top:80px;width:1024px;float:left;text-align:left;border-style:solid;border-width:2px;min-height:750px">
<?php
	if(isset($_GET['arg1']))
	{
		require_once('display.php');
		switch ($_GET['arg1'])
		{
			case 'upload':
				define('UPLOAD_HERE',1);
				require_once("nooneknowme.php");
				break;
			case 'chi':
				switch ($_GET['arg2'])
				{
					case 'them':
						if(!isset($_GET['action']) || $_GET['action'] != 'do')
							display::displayNewPC();
						else
						{
							require_once('phieuchi.php');
							$obj = new phieuchi();
							$obj->chitiet = $_POST['noidung'];
							$obj->giatri = $_POST['giatri'];
							$rs = $obj->save();
							if(!$rs)
							{
								$hdr = "Location: admin.php?arg1=chi&arg2=xem&msg=".base64_encode("Thêm khoản chi không thành công");
								header($hdr);
							}
							else
							{
								$hdr = "Location: admin.php?arg1=chi&arg2=xem&msg=".base64_encode("Đã thêm khoản chi mới");
								header($hdr);
							}
						}
						break;
					case 'xoa':
						require_once('phieuchi.php');
						$obj = new phieuchi($_GET['arg3']);
						if($obj->remove())
						{
							$hdr = "Location: admin.php?arg1=chi&arg2=xem&msg=".base64_encode("Đã xóa phiếu chi #".$_GET['arg3']);
							header($hdr);	
						}
						else
						{
							$hdr = "Location: admin.php?arg1=chi&arg2=xem&msg=".base64_encode("Không thể xóa phiếu chi #".$_GET['arg3']);
							header($hdr);	
						}
						break;
					default:
						require_once('phieuchi.php');
						$rs = phieuchi::loadAll();
						echo display::displayAllPC($rs);
						break;
				}
				break;
			case 'thongke':
				require_once('hoadon.php');
				require_once('phieuchi.php');
				switch ($_GET['arg2'])
				{
					case 'all':
						$rs = hoadon::loadOnCondition();
						echo "<div id='result'>";
						echo display::displayHD($rs);
						echo "</div>";
						break;
					case 'nam':
					case 'thang':
					case 'ngay':
						$y = isset($_POST['year']) ? $_POST['year'] : date('Y');
						$m = isset($_POST['month']) ? $_POST['month'] : date('m');
						$d = isset($_POST['day']) ? $_POST['day'] : date('d');
						echo "<form method='POST' action='".$_SERVER['REQUEST_URI']."'>";
						if($_GET['arg2'] == 'ngay')
							display::displayDaySelection('day',$d);
						if($_GET['arg2'] == 'thang' || $_GET['arg2'] == 'ngay' )
							display::displayMonthSelection('month',$m);
						
						display::displayYearSelection('year',$y);
						
						
						echo "<input type=submit value='Search...' />";	
						if(isset($_POST['year']))
						{
							$y = $_POST['year'];
							$m = isset($_POST['month']) ? $_POST['month'] : "";
							$d = isset($_POST['day']) ? $_POST['day'] : "";
							if($d != "")
							{
								$rs = hoadon::loadOnCondition($y,$m,$d);
								$rs1 = phieuchi::loadAll($y,$m,$d);
							}
							else if($m != "")
							{
								$rs = hoadon::loadOnCondition($y,$m);
								$rs1 = phieuchi::loadAll($y,$m);
							}
							else
							{
								$rs = hoadon::loadOnCondition($y);
								$rs1 = phieuchi::loadAll($y);
							}
							echo "<div id='result'>";
							echo display::displayHD($rs);
							echo display::displayAllPC($rs1);
							echo "</div>";
						}
						break;
					case 'khoan':
						$y = isset($_POST['year']) ? $_POST['year'] : date('Y');
						$m = isset($_POST['month']) ? $_POST['month'] : date('m');
						$d = isset($_POST['day']) ? $_POST['day'] : date('d');
						$y1 = isset($_POST['year1']) ? $_POST['year1'] : date('Y');
						$m1 = isset($_POST['month1']) ? $_POST['month1'] : date('m');
						$d1 = isset($_POST['day1']) ? $_POST['day1'] : date('d');
						echo "<form method='POST' action='".$_SERVER['REQUEST_URI']."'>";
						echo "Ngày bắt đầu:  &nbsp;";
						display::displayDaySelection('day',$d);
						display::displayMonthSelection('month',$m);
						display::displayYearSelection('year',$y);
						echo "<br />";
						echo "Ngày kết thúc: ";
						display::displayDaySelection('day1',$d1);
						display::displayMonthSelection('month1',$m1);
						display::displayYearSelection('year1',$y1);
						echo "<input type=submit value='Search...' />";	
						if(isset($_POST['year']))
						{
							
							$rs = hoadon::loadOnCondition($y,$m,$d,$y1,$m1,$d1);
							echo "<h3> Tổng hợp hóa đơn (thu) </h3>";
							echo "<div id='result'>";
							echo display::displayHD($rs);
							echo "</div>";
							echo "<div id='userstat'>";
							echo "<h3> Tổng hợp tiền công </h3>";
							echo "<table width=100% border=1>";
							echo "<tr><th>Tên nhân viên</th><th>Tổng thu</th><th>Tống chiết khấu</th><th>Tiền công</th></tr>";
							require_once('user.php');
							$users = user::loadAll();
							while($r = mysql_fetch_array($users))
							{
								$nguoilap = $r['username'];
								if($nguoilap == 'admin')
									continue;
								$dmy = $y."-".$m."-".$d;
								$dmy1 = $y1."-".$m1."-".$d1;
								$info = hoadon::loadAllByCreatorAndPeriod($nguoilap,$dmy,$dmy1);
								$r1 = mysql_fetch_array($info);
								$tiencong = ($r1['stotal'] - $r1['schietkhau']) / 2;
								echo "<tr align='center'><td>".$nguoilap."</td><td>".$r1['stotal']."</td><td>".$r1['schietkhau']."</td><td>".$tiencong."</td></tr>";

							}
							echo "</table>";
							echo "<ul><li style='color:red;font-weight:bold'>Tiền công = (tổng thu - tổng chiết khấu) / 2 </li></ul>";
							echo "</div>";
						}
						break;
					case 'nhanvien':
						require_once('user.php');
						$rs = user::loadAll();
						if(!$rs)
						{
							die();
						}
						echo "<form method='POST' action='".$_SERVER['REQUEST_URI']."'>";
						echo "Chọn 1 nhân viên:  ";
						display::displayUserSelection($rs);
						echo "<input type=submit value='Search...' />";	
						if(isset($_POST['user']))
						{
							$u = $_POST['user'];
							$rs = hoadon::loadOnCondition($u,null,null,null);
							echo "<div id='result'>";
							echo display::displayHD($rs);
							echo "</div>";
						}
						break;
					default:
						break;
				}
				break;
			case 'nhan-vien':
				require_once("user.php");
				require_once("config.php");
				switch ($_GET['arg2']) {
					case 'them':
						if(!isset($_GET['action']) || $_GET['action'] != 'do')
						{
							display::displayNewNV();
						}
						else
						{
							$obj = new user($_POST['username'],$_POST['password']);
							$rs = $obj->addUser(); 
							$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem";
							if($rs)
							{
								$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Thêm nhân viên thành công");
							}
							else
							{
								$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Không thành công. Bị trùng tên?");
							}
							header($hdr);
						}
						break;
					case 'reset':
						$obj = new user($_GET['arg3'],$resetpassword);
						$rs = $obj->updatePassword();
						if($rs)
						{
							$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Password had been reset to: ".$resetpassword."");
							header($hdr);
						}
						else
						{
							$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Reset password failed");
							header($hdr);
						}
						break;
					case 'nghiviec':
						$obj = new user($_GET['arg3'],"");
						$obj->updateStatus(0);
						if($rs)
						{
							$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Cập nhật thành công");
							header($hdr);
						}
						else
						{
							$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Cập nhật thất bại");
							header($hdr);
						}
						break;
					case 'trolai':
						$obj = new user($_GET['arg3'],"");
						$obj->updateStatus(1);
						if($rs)
						{
							$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Cập nhật thành công");
							header($hdr);
						}
						else
						{
							$hdr = "Location: admin.php?arg1=nhan-vien&arg2=xem&msg=".base64_encode("Cập nhật thất bại");
							header($hdr);
						}
						break;
					case 'xemnghiviec':
						if(isset($_GET['msg']))
						{
							echo "<font color='red'>".base64_decode($_GET['msg'])."</font><br />";
						}
						$rs = user::loadAll(0);
						if(!$rs)
						{
							echo "SQL error 0x00008001";
							die();
						}
						if(mysql_num_rows($rs) == 0)
						{
							echo "Không có nhân viên";
							die();
						}
						echo "<table width=50%>";
						echo "<tr><td>Tên nhân viên</td><td>Chức năng</td></tr>";
						while($r = mysql_fetch_array($rs))
						{
							echo "<tr><td>".$r['username']."</td><td><a href='admin.php?arg1=nhan-vien&arg2=reset&arg3=".$r['username']."'> Reset password </a> | <a href='admin.php?arg1=nhan-vien&arg2=trolai&arg3=".$r['username']."'> Trở lại làm việc </a></td></tr>";
						}
						echo "</table>";
						break;
					case 'xem':
					default:
						if(isset($_GET['msg']))
						{
							echo "<font color='red'>".base64_decode($_GET['msg'])."</font><br />";
						}
						$rs = user::loadAll();
						if(!$rs)
						{
							echo "SQL error 0x00008001";
							die();
						}
						if(mysql_num_rows($rs) == 0)
						{
							echo "Không có nhân viên";
							die();
						}
						echo "<table width=50% border=1 style='border-width: 3px;'>";
						echo "<tr><td>Tên nhân viên</td><td>Chức năng</td></tr>";
						while($r = mysql_fetch_array($rs))
						{
							echo "<tr><td>".$r['username']."</td><td><a href='admin.php?arg1=nhan-vien&arg2=reset&arg3=".$r['username']."'>[Reset password]</a> ::|:: <a href='admin.php?arg1=nhan-vien&arg2=nghiviec&arg3=".$r['username']."'>[Cho thôi việc]</a></td></tr>";
						}
						echo "</table>";
						break;
				}
			case 'cau-hinh':
				break;
			case 'dich-vu':
				switch ($_GET['arg2'])
				{
					case 'them':
						if(!isset($_GET['action']) || $_GET['action'] != 'do' )
						{
							display::displayNewDV();
						}
						else if(isset($_GET['action']) && $_GET['action'] == "do")
						{
							require_once('item.php');
							$obj = new item();
							$obj->add($_POST['dichvu'],$_POST['dongia'],$_POST['chietkhau']);
							header("Location: admin.php?arg1=dich-vu&arg2=xem");
						}
						break;
					case 'sua':
						require_once('item.php');
						if(isset($_GET['action']))
						{
							$madv = $_POST['madv'];
							$obj = new item($madv);
							$obj->update($_POST['dichvu'],$_POST['dongia'],$_POST['chietkhau']);
							header("Location: admin.php?arg1=dich-vu&arg2=xem");
							return;
						}
						$obj = new item($_GET['masp']);
						$rs = $obj->loadItem();
						display::displayDV($rs);
						break;
					case 'xem':
						require_once('item.php');
						$rs = item::loadAllItem();
						if(!$rs) 
							echo "<h4> Chưa có dịch vụ nào. <a href='admin.php?arg1=dich-vu&arg2=them'>Thêm ngay</a></h4>";
						echo display::displayAllDV($rs);
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