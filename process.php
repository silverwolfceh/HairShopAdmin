<?php
	if(!isset($_GET['optcode']) && !isset($_POST['optcode']))
		die();
	$optcode = $_REQUEST['optcode'];
	switch ($optcode)
	{
		case 'login':
			require_once('user.php');
			//echo $_REQUEST['username']."-".$_REQUEST['password'];
			$obj = new user($_REQUEST['username'],$_REQUEST['password']);
			$rs = $obj->login();
			if($rs == true)
				echo "OK";
			else
				echo "NG";
			break;
		case 'luu-hoa-don':
			require_once('hoadon.php');
			$kh = $_POST['khachhang'];
			$prices = $_POST['prices'];
			$ids = $_POST['ids'];
			$obj = new hoadon();
			$obj->khachhang = $kh;
			$parts = explode(":", $prices);
			$part1s = explode(":", $ids);
			for($i=0;$i < count($parts);$i ++ )
			{
				$obj->addChitiet($part1s[$i],$parts[$i]);
			}
			$obj->save();
			echo $obj->getLastHD();
			break;
		case 'in-hoa-don':
			require_once('hoadon.php');
			require_once('config.php');
			require_once('chitiet.php');
			require_once('item.php');
			$mahd = $_POST['mahd'];
			$obj = new hoadon($mahd);
			$rs = $obj->load();
			if(!$rs)
				echo "Error";
			$r = mysql_fetch_array($rs);
			$output = "<div style='border: 2px solid;width:500px;'>";
			$output .= "<center><h3> Phiếu Thanh Toán </h3></center>";
			$output .= "<center><h4> $tentiem </h4></center><center>";
			$output .= "Địa chỉ: ".$diachi. "<br />";
			$output .= "Điện thoại: ".$dienthoai. "<br /><br /><br /></center>";
			$output .= "<table width=500px border=0>";
			$output .= "<tr><td align='right'>Ngày lập phiếu: </td><td>".$r['ngaylap']."</td></tr>";
			$output .= "<tr><td align='right'>Người lập phiếu: </td><td>_".$_SESSION['uname']."_</td></tr>";
			$output .= "<tr><td colspan=2 ><br /><center><b>Chi tiết dịch vụ </b></center></td></tr>";
			
			$obj1 = new chitiet($mahd);
			$rs1 = $obj1->load();
			$obj2 = new item();
			if($rs1)
			{
				while($r1 = mysql_fetch_array($rs1))
				{
					$output.= "<tr><td colspan=2 align='center'>".$obj2->getName($r1[1]).".................".$r1[2]." VNĐ</td></tr>";
				}
			}
			$output .= "<tr><td colspan=2 ><br /></td></tr>";
			$output .= "<tr><td colspan=2 align='center' style='color:red'><b> Tổng cộng:.................".$r['total']." VNĐ</b></td></tr>";
			$output .= "<tr ><td colspan=2 align='right' ><br /><i> Cám ơn quý khách, hẹn gặp lại </i></td></tr>";

			$output .= "</table></div>";
			echo $output;
			break;
		case 'filter':
			$m = $_POST['m'];
			$y = $_POST['y'];
			require_once("hoadon.php");
			$rs = hoadon::filter($m,$y);
			if(!$rs)
				die();
			$total = 0;
			$output  = "<table border=0 style='width:500px'>";
			$output .= "<tr><th>Mã hóa đơn</th><th> Ngày lập </th><th> Khách hàng </th><th> Trị giá </th><th> Công cụ </th></tr>";
			while($r = mysql_fetch_array($rs))
			{
				$output .= "<tr>";
				$output .= "<td align='center'>".$r['mahd']."</td>";
				$output .= "<td align='center'>  ".$r['ngaylap']." </td>";
				$output .= "<td align='center'>  ".$r['khachhang']." </td>";
				$output .= "<td align='center'> ".$r['total']." </td>";
				$output .= '<td align="center"> <a href="#"" onclick=\'setandprint("'.$r['mahd'].'");\'>View </a> </td>';
				$output .= "</tr>";
				$total += $r['total'];
			}
			$output .= "</table>";
			$output .= "<p> Có tất cả ".mysql_num_rows($rs)." hóa đơn với tổng trị giá: ".$total."</p>";
			echo $output;
		default:
			# code...
			break;
	}
?>