<?php
	@session_start();
	require_once("config.php");
	require_once("chitiet.php");
	class hoadon
	{
		private $action;
		private $mahd;
		private $chitiet;
		private $chitiet_ck;
		public $khachhang;
		private $chietkhau;
		public $nguoilap;
		public function __construct($maso = -1)
		{
			$this->mahd = $maso;
			if(isset($_SESSION['uname']))
				$this->nguoilap = $_SESSION['uname'];
		}
		public static function loadAll()
		{
			if(func_num_args() == 0)
			{
				return hoadon::loadAllNoCondition();
			}
			elseif (func_num_args() == 1)
			{
				return hoadon::loadAllByCreator(func_get_arg(0));
			}
			elseif (func_num_args() == 2) {
				return hoadon::loadAllByCreatorAndDate(func_get_arg(0),func_get_arg(1));
			}
		}
		public static function loadOnCondition()
		{
			switch (func_num_args())
			{
				case 0:
					return hoadon::loadAllNoCondition();
					break;
				case 1:
					return hoadon::loadAllByYear(func_get_arg(0));
					break;
				case 2:
					return hoadon::loadAllByMonth(func_get_arg(0),func_get_arg(1));
					break;
				case 3: 
					return hoadon::loadAllByDay(func_get_arg(0),func_get_arg(1),func_get_arg(2));
					break;
				case 4:
					return hoadon::loadAllByCreator(func_get_arg(0));
					break;
				case 6:
					return hoadon::loadAllByRange(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4),func_get_arg(5));
				default:
					return null;
					break;
			}
		}
		public static function loadAllNoCondition()
		{
			$sql = "SELECT * FROM hoadon ORDER BY ngaylap DESC";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByCreator($nguoilap)
		{
			$sql = "SELECT * FROM hoadon WHERE nguoilap = '".$nguoilap."' ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByCreatorAndDate($nguoilap,$ngaylap)
		{
			$sql = "";
			if($nguoilap != "")
				$sql = "SELECT * FROM hoadon WHERE nguoilap = '".$nguoilap."' AND ngaylap = '".$ngaylap."' ORDER BY ngaylap DESC;";
			else
				$sql = "SELECT * FROM hoadon WHERE ngaylap = '".$ngaylap."' ORDER BY ngaylap DESC;";

			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByYear($Y)
		{
			$sql = "SELECT * FROM hoadon WHERE ngaylap LIKE '".$Y."-%' ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByMonth($Y,$M)
		{
			$sql = "SELECT * FROM hoadon WHERE ngaylap LIKE '".$Y."-".$M."-%' ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByDay($Y,$M,$D)
		{
			$sql = "SELECT * FROM hoadon WHERE ngaylap LIKE '".$Y."-".$M."-".$D."' ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByRange($Y,$M,$D,$Y1, $M1,$D1)
		{
			$sql = "SELECT * FROM hoadon WHERE ngaylap >= '".$Y."-".$M."-".$D."' AND ngaylap <= '".$Y1."-".$M1."-".$D1."'  ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public function getLastHD()
		{
			return $this->mahd;
		}
		public function addChitiet($item,$price,$discount = 0)
		{
			$this->chitiet[$item] = $price;
			$this->chitiet_ck[$item] = $discount;
		}
		public function addChitiet1($mix)
		{
			$parts = explode(":", $mix);
			$this->addChitiet($parts[0],$parts[1]);
		}
		public function save()
		{
			$sql = "SELECT max(mahd) as mahdmax FROM hoadon";
			$rs = mysql_query($sql);
			if(!$rs)
				$this->mahd = 1;
			$r = mysql_fetch_array($rs);
			$this->mahd = $r['mahdmax'] + 1;
			$sql = "INSERT INTO hoadon(mahd,total,chietkhau,ngaylap,nguoilap,khachhang,hople) VALUES(".$this->mahd.",0,0,'".date('Y-m-d')."','".$this->nguoilap."','".$this->khachhang."',1);";
			$rs = mysql_query($sql);
			if($rs)
			{
				$obj = new chitiet($this->mahd);
				foreach($this->chitiet as $k => $val)
				{
					$obj->addItem($k,$val,$this->chitiet_ck[$k]);
					$obj->save();
				}
				$total = $obj->getTotal();
				$ck = $obj->getChietKhau();
				$sql = "UPDATE hoadon SET total=".$total.", chietkhau = ".$ck." WHERE mahd = ".$this->mahd;
				mysql_query($sql);

			}
		}
		public function load()
		{
			$sql = "SELECT * FROM hoadon WHERE mahd = ".$this->mahd.";";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function filter($m,$y)
		{
			$sql = "SELECT * FROM hoadon WHERE ngaylap like '".$y."-".$m."%' order by ngaylap DESC";
			$rs = mysql_query($sql);
			return $rs;
		}

		public function delete()
		{
			$obj = new chitiet($this->mahd);
			$obj->deleteAll();
			$sql = "DELETE FROM hoadon WHERE mahd = ".$this->mahd;
			mysql_query($sql);
		}
	}
?>