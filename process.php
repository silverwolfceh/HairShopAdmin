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
			{
				if(md5($obj->orguname) == md5($_REQUEST['username']))
					echo "OK";
				else
					echo "Good try but not luck this time ^_^ #ANBU";
			}
			else
				echo "NG";
			break;
		case 'update-password':
			if(!isset($_SESSION['uname']))
			{
				echo "Nice try but not luck! ;))";
				die();
			}
			require_once('user.php');
			$obj = new user($_POST['username'],$_POST['password']);
			$rs = $obj->updatePassword();
			if($rs)
				echo "ok";
			else
				echo "er";
			break;
		case 'luu-hoa-don':
			require_once('hoadon.php');
			$kh = $_POST['khachhang'];
			$prices = $_POST['prices'];
			$ids = $_POST['ids'];
			$cks = $_POST['cks'];
			$nguoilap = $_POST['nguoilap'];
			$obj = new hoadon();
			$obj->khachhang = $kh;
			$obj->nguoilap = $nguoilap;
			$price_parts = explode(":", $prices);
			$id_parts = explode(":", $ids);
			$discount_parts = explode(":", $cks);
			for($i=0;$i < count($id_parts);$i ++ )
			{
				$obj->addChitiet($id_parts[$i],$price_parts[$i],$discount_parts[$i]);
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
			$inngay = ($_POST['in'] == 1 ? true : false);
			$obj = new hoadon($mahd);
			$rs = $obj->load();
			if(!$rs)
				echo "Error";
			$r = mysql_fetch_array($rs);
			$output = "<style>";
			$output .= "table,th,td { border: 2px solid black;margin-right:100px;border-collapse: collapse; };";
			$output .= "</style>";
			$output .= "<div style='margin-top:-100px;margin-right:100px;width:auto !important'>";
			$output .= "<div style='border: 0px solid;'>";
			$output .= "<center><h3> $tentiem </h3></center>";
			$output .= "<center><b> $diachi </b></center><br />";
			$output .= "<center><i> Tên nhân viên: ".$r['nguoilap']." </b></center><br />";
			//$output .= "<center style='font-size:400%'> $dienthoai </center>";
			//$output .= "<center><hr style='width:180px; border: 0px dot' /></center>";
			//$output .= "<center style='font-size:400%'> Phiếu Thanh Toán <br /></center>";
			//$output .= "<table width=100% border=0 style='margin: auto;'>";
			//$output .= "<tr style='font-size:400%'><td align='center'>Ngày: </td><td>".$r['ngaylap']."</td></tr>";
			//$output .= "<tr style='font-size:400%'><td align='center'>Nhân viên: </td><td>".$r['nguoilap']."</td></tr>";
			//$output .= "<tr style='font-size:400%'><td colspan=2 align='center' ><br /><center><b>Chi tiết dịch vụ </b></center></td></tr>";
			//$output .= "</table>";
			$output .= "<table width=100% style='border: 2px solid black;margin-right:100px;border-collapse: collapse;'>";
			$output .= "<tr style='font-size:400%' ><th width=10% >MÃ DV</th><th width=60% >DỊCH VỤ</th><th >GIÁ (VNĐ)</th></tr>";
			$obj1 = new chitiet($mahd);
			$rs1 = $obj1->load();
			$obj2 = new item();
			if($rs1)
			{
				$isMerge = false;
				$rc = mysql_num_rows($rs1);
				$r1 = true;
				$cnt = 0;
				while($r1 = mysql_fetch_array($rs1))
				{
					$styling = 'border-bottom-color:#ccc';
					$cnt ++;
					if($cnt == $rc)
					{
						$styling = 'border-bottom-color:black';
					}
					if(!$isMerge)
						$output.= "<tr style='font-size: 400%;".$styling."'><td rowspan=$rc>$mahd</td>";
					else
						$output.= "<tr style='font-size: 400%;".$styling."'>";	
					$output.= "<td align='left' style='".$styling."'>".$obj2->getName($r1[1])."</td><td align='right' style='".$styling."'>".$r1[2]."</td></tr>";
					$isMerge = true;				

				}
			}
			//$output .= "<tr><td style='border-bottom-color:black' ></td><td style='border-bottom-color:black'></td><td style='border-bottom-color:black'></td></tr>";
			$output .= "<tr style='font-size: 400%;'><td colspan=2 rowspan =2 align='center' >TỔNG CỘNG</td><td rowspan=2 align='right' style='border-top-color:black'>".$r['total']."</td></tr>";
			//$output .= "<tr><td colspan=2 style='border:0px' ><br /></td></tr>";
			//$output .= "<tr><td colspan=2 style='border:0px' ><br /></td></tr>";
			//$output .= "<tr style='font-size:400%'><td colspan=2 align='center' style='color:red;border:0px'><b> Tổng cộng:.................".$r['total']."</b></td></tr>";
			#$output .= "<tr style='font-size:400%'><td colspan=2 align='center' style='border:0px' ><br /><i> Cám ơn quý khách, hẹn gặp lại </i></td></tr>";

			$output .= "</table>";
			$output .= "<center style='font-size:100%'><i>Hân hạnh được phục vụ quý khách</i></center>";
			$output .= "<center style='font-size:100%'><i>Hẹn gặp lại quý khách</i></center>";
			if($inngay)
				$output .= "<script>window.print();</script>";
			else
			{
				$output .= "<script>function print_bnt() { document.getElementById('printbtn').style.visibility = 'hidden'; window.print(); }</script>";
				$output .= "<center><input type='button' id='printbtn' value='In phiếu' onclick='print_bnt()' style='font-size:100%;height:100px;width:300px;background-color: #00ff00;color:#ff0000' /><center>";
			}
			$output .= "</div>";
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