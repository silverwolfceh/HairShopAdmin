<?php
	@session_start();
	require_once("config.php");
	require_once("chitiet.php");
	class hoadon
	{
		private $action;
		private $mahd;
		private $chitiet;
		public $khachhang;
		private $chietkhau;
		public function __construct($maso = -1)
		{
			$this->mahd = $maso;
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
		}
		public static function loadAllNoCondition()
		{
			$sql = "SELECT * FROM hoadon";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByCreator($nguoilap)
		{
			$sql = "SELECT * FROM hoadon WHERE nguoilap = '".$nguoilap."';";
			$rs = mysql_query($sql);
			return $rs;
		}
		public function getLastHD()
		{
			return $this->mahd;
		}
		public function addChitiet($item,$price)
		{
			$this->chitiet[$item] = $price;
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
			$sql = "INSERT INTO hoadon(mahd,total,chietkhau,ngaylap,nguoilap,khachhang,hople) VALUES(".$this->mahd.",0,0,'".date('Y-m-d')."','".$_SESSION['uname']."','".$this->khachhang."',1);";
			$rs = mysql_query($sql);
			if($rs)
			{
				$obj = new chitiet($this->mahd);
				foreach($this->chitiet as $k => $val)
				{
					$obj->addItem($k,$val);
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