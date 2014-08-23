<?php
	class display
	{
		public static function displayHD($rs)
		{
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
				$output .= '<td align="center"> <a href="#"" onclick=\'setandprint("'.$r['mahd'].'");\'>View </a> </td>';
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
				$ouput .= "<td align='center'><a href='admin.php?arg1=dich-vu&arg2=xoa&masp=".$r['masp']."'>Xóa</a></td>";
				$ouput .= "</tr>";
			}
			return $ouput;
		}
		public static function displayYearSelection($name = 'year')
		{
			echo "Select a year to view: <select name='$name'>";
			for($i=2014;$i<=2018;$i++)
			{
				echo "<option value='".$i."'>".$i."</option>";	
			}
			echo "</select>";
		}
		public static function displayMonthSelection($name = 'month')
		{
			echo "<select name='$name'>";
			for($i=1;$i<=12;$i++)
			{
				if($i < 10)
					echo "<option value='0".$i."'>".$i."</option>";	
				else
					echo "<option value='".$i."'>".$i."</option>";	
			}
			echo "</select>";
		}
		public static function displayDaySelection($name = 'day')
		{
			echo "<select name='$name'>";
			for($i=1;$i<=31;$i++)
			{
				if($i < 10)
					echo "<option value='0".$i."'>".$i."</option>";	
				else
					echo "<option value='".$i."'>".$i."</option>";	
			}
			echo "</select>";
		}
		public static function displayUserSelection($rs)
		{
			echo "<select name='user'>";
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
			$output .= "<tr><th>Mã khoản chi</th><th> Ngày lập </th><th> Nội dung </th><th> Trị giá </th></tr>";
			$total = 0;
			$chietkhau = 0;
			$numhd = 0;
			while($r = mysql_fetch_array($rs))
			{
				$output .= "<tr>";
				$output .= "<td align='center'>".$r['maso']."</td>";
				$output .= "<td align='center'>  ".$r['ngaylap']." </td>";
				$output .= "<td align='center'>  ".$r['noidung']." </td>";
				$output .= "<td align='center'> ".$r['giatri']." </td>";
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