<?php
	class display
	{
		public static function displayHD($rs)
		{
			if(isset($_GET['msg']))
			{
				echo "<font color='red'>".base64_decode($_GET['msg'])."</font><br />";
			}
			$output = "<hr />";
			$output .= "<table border=1 width=100%>";
			$output .= "<tr><th>Mã hóa đơn</th><th> Ngày lập </th><th> Nhân Viên </th><th> Trị giá </th><th> Chiết khấu </th><th> Công cụ </th></tr>";
			$total = 0;
			$chietkhau = 0;
			$numhd = 0;
			while($r = mysql_fetch_array($rs))
			{
				$output .= "<tr>";
				$output .= "<td align='center'>".$r['mahd']."</td>";
				$output .= "<td align='center'>  ".$r['ngaylap']." </td>";
				$output .= "<td align='center'>  ".$r['nguoilap']." </td>";
				$output .= "<td align='center'> ".$r['total']." </td>";
				$output .= "<td align='center'> ".$r['chietkhau']." </td>";
				$output .= '<td align="center"> <a href="#"" onclick=\'setandprint("'.$r['mahd'].'");\'>View </a> | <a href="admin.php?arg1=hoadon&arg2=xoa&arg3='.$r['mahd'].'"> Delete</a></td>';
				$output .= "</tr>";
				$total += $r['total'];
				$chietkhau += $r['chietkhau'];
				$numhd += 1;
			}
			$output .= "</table>";
			$output .= "<hr />";
			$output .= "<ul style='color:red;'>";
			$output .= "<li>Số hóa đơn: <b>".$numhd."</b></li>";
			$output .= "<li>Tổng thu: <b>".$total."</b></li>";
			$output .= "<li>Số tiền Admin được hưởng: <b>".$chietkhau."</b></li>";
			$output .= "</ul>";
			return $output;
		}
		public static function displayNewDV()
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
		public static function displayAllCustomer($rs)
		{
			$ouput = "<table width=100% border=1>";
			$ouput .= "<tr><th> Tên </th><th > Số ĐT </th><th> Dịch vụ sử dụng </th><th>Lần sử dụng gần nhất</th></tr>";
			while ($r = mysql_fetch_array($rs))
			{
				$ouput .= "<tr>";
				$ouput .= "<td align='center'>".$r['hoten']."</td>";
				$ouput .= "<td align='center'>".$r['sodt']."</td>";
				$ouput .= "<td align='right'>".item::getListOfItem($r['service'])."</td>";
				$recent = customer::calculateDayDiff($r['lasttime']);
				if($recent > 0)
					$ouput .= "<td align='center'>".customer::calculateDayDiff($r['lasttime'])." ngày</td>";
				else
					$ouput .= "<td align='center'>hôm nay</td>";
				//$ouput .= "<td align='center'><a href='admin.php?arg1=dich-vu&arg2=xoa&masp=".$r['masp']."'>Xóa</a>:-:<a href='admin.php?arg1=dich-vu&arg2=sua&masp=".$r['masp']."'>Sửa</a></td>";
				$ouput .= "</tr>";
			}
			return $ouput;
		}
		public static function displayAllDV($rs)
		{
			$ouput = "<table width=50% border=1>";
			$ouput .= "<tr><th> Mã số </th><th > Tên dịch vụ </th><th> Giá </th><th> Chiết khấu </th><th> Công cụ </th></tr>";
			while ($r = mysql_fetch_array($rs))
			{
				$ouput .= "<tr>";
				$ouput .= "<td align='center'>".$r['masp']."</td>";
				$ouput .= "<td align='left'>".$r['tensp']."</td>";
				$ouput .= "<td align='right'>".$r['giamacdinh']."</td>";
				$ouput .= "<td align='right'>".$r['chietkhau']."</td>";
				$ouput .= "<td align='center'><a href='admin.php?arg1=dich-vu&arg2=xoa&masp=".$r['masp']."'>Xóa</a>:-:<a href='admin.php?arg1=dich-vu&arg2=sua&masp=".$r['masp']."'>Sửa</a></td>";
				$ouput .= "</tr>";
			}
			return $ouput;
		}
		public static function displayDV($rs)
		{
			if(mysql_num_rows($rs) != 1)
			{
				echo "Dịch vụ không đúng";
				return;
			}
			$r = mysql_fetch_array($rs);
			echo "<center>";
			echo "<h3> Chỉnh sửa dịch vụ </h3>";
			echo "<form method='post' action='admin.php?arg1=dich-vu&arg2=sua&action=do'>";
			echo "<input type='text' name='dichvu' id='dichvu' placeholder='Tên dịch vụ' value='".$r['tensp']."' /><br /><br />";
			echo "<input type='text' name='dongia' id='dongia' placeholder='Đơn giá' value='".$r['giamacdinh']."' /><br /><br />";
			echo "<input type='text' name='chietkhau' id='chietkhau' placeholder='Chia admin' value='".$r['chietkhau']."' /><br />";
			echo "<input type='hidden' name='back' id='back' value='admin.php?arg1=dich-vu' /><br />";
			echo "<input type='hidden' name='madv' id='madv' value='".$r['masp']."' /><br />";
			echo "<input type='submit' value='Lưu' /></form>";
			echo "</center>";
		}
		public static function displayYearSelection($name = 'year',$val = 2014)
		{
			echo "<select name='$name'>";
			for($i=2014;$i<=2018;$i++)
			{
				if($i == $val)
					$selected = "selected";
				else
					$selected = "";
				echo "<option value='".$i."' $selected>".$i."</option>";	
			}
			echo "</select>";
		}
		public static function displayMonthSelection($name = 'month',$val = 1)
		{
			$selected = "";
			echo "<select name='$name'>";
			for($i=1;$i<=12;$i++)
			{
				if($i == $val)
					$selected = "selected";
				else
					$selected = "";
				if($i < 10)
					echo "<option value='0".$i."' $selected>".$i."</option>";	
				else
					echo "<option value='".$i."' $selected>".$i."</option>";	
			}
			echo "</select>";
		}
		public static function displayDaySelection($name = 'day',$val = 1)
		{
			$selected = "";
			echo "<select name='$name'>";
			for($i=1;$i<=31;$i++)
			{
				if($i == $val)
					$selected = "selected";
				else
					$selected = "";
				if($i < 10)
					echo "<option value='0".$i."' $selected>".$i."</option>";	
				else
					echo "<option value='".$i."' $selected>".$i."</option>";	
			}
			echo "</select>";
		}
		public static function displayUserSelection($rs)
		{
			echo "<select name='user' id='user'>";
			while($r = mysql_fetch_array($rs))
			{
				echo "<option value='".$r['username']."'>".$r['username']."</option>";
			}
			echo "</select>";
		}
		public static function displayNewNV()
		{
			echo "<center>";
			echo "<h3> Thêm nhân viên mới </h3>";
			echo "<form method='post' action='admin.php?arg1=nhan-vien&arg2=them&action=do'>";
			echo "<input type='text' name='username' id='username' placeholder='Username' /><br /><br />";
			echo "<input type='text' name='password' id='password' placeholder='Password' /><br /><br />";
			echo "<input type='submit' value='Lưu' /></form>";
			echo "</center>";
		}
		public static function displayNewPC()
		{
			echo "<center>";
			echo "<h3> Thêm khoản chi </h3>";
			echo "<form method='post' action='admin.php?arg1=chi&arg2=them&action=do'>";
			echo "<input type='text' name='noidung' id='noidung' placeholder='Nội dung chi' /><br /><br />";
			echo "<input type='text' name='giatri' id='giatri' placeholder='Số tiền chi' /><br /><br />";
			echo "<select name='loai'>";
			echo "<option value='Tiem'>Tiệm</option>";
			echo "<option value='NauAn'>Nấu ăn</option>";
			echo "</select>";
			echo "<input type='submit' value='Lưu' /></form>";
			echo "</center>";
		}
		public static function displayAllPC($rs)
		{
			if(isset($_GET['msg']))
			{
				echo "<font color='red'>".base64_decode($_GET['msg'])."</font><br />";
			}
			$output = "<table border=1 width=100%>";
			$output .= "<tr><th>Mã khoản chi</th><th> Ngày lập </th><th> Nội dung </th><th>Loại</th><th> Trị giá </th><th>Chức năng</th></tr>";
			$total = 0;
			$chietkhau = 0;
			$numhd = 0;
			while($r = mysql_fetch_array($rs))
			{
				$output .= "<tr>";
				$output .= "<td align='center'>".$r['maso']."</td>";
				$output .= "<td align='center'>  ".$r['ngaylap']." </td>";
				$output .= "<td align='center'>  ".$r['noidung']." </td>";
				$output .= "<td align='center'>  ".($r['loai'] == "Tiem" ? "Tiệm" : "Nấu ăn")." </td>";
				$output .= "<td align='center'> ".$r['giatri']." </td>";
				$output .= "<td align='center'> <a href='admin.php?arg1=chi&arg2=xoa&arg3=".$r['maso']."'> Xóa </a> </td>";
				$output .= "</tr>";
				$total += $r['giatri'];
				$numhd += 1;
			}
			$output .= "</table>";
			$output .= "<hr />";
			$output .= "<ul style='color:red;'>";
			$output .= "<li>Số khoản chi: <b>".$numhd."</b></li>";
			$output .= "<li>Tổng chi: <b>".$total."</b></li>";
			$output .= "</ul>";
			return $output;
		}
	}
?>