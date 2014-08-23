<?php
	@session_start();
	require_once("config.php");
	class phieuchi
	{
		private $mahd;
		public $chitiet;
		public $giatri;
		public function __construct($maso = -1)
		{
			$this->mahd = $maso;
		}
		public static function loadAll()
		{
			switch (func_num_args()) 
			{
				case 0:
					return phieuchi::loadAllNoCondition();
					break;
				case 1:
					return phieuchi::loadAllByYear(func_get_arg(0));
					break;
				case 2:
					return phieuchi::loadAllByMonth(func_get_arg(0),func_get_arg(1));
					break;
				case 3: 
					return phieuchi::loadAllByDay(func_get_arg(0),func_get_arg(1),func_get_arg(2));
					break;
				case 6:
					return phieuchi::loadAllByRange(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4),func_get_arg(5));
				default:
					return null;
					break;
			}
		}
		public static function loadAllNoCondition()
		{
			$sql = "SELECT * FROM phieuchi ORDER BY ngaylap DESC";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByYear($Y)
		{
			$sql = "SELECT * FROM phieuchi WHERE ngaylap LIKE '".$Y."-%' ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByMonth($Y,$M)
		{
			$sql = "SELECT * FROM phieuchi WHERE ngaylap LIKE '".$Y."-".$M."-%' ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByDay($Y,$M,$D)
		{
			$sql = "SELECT * FROM phieuchi WHERE ngaylap LIKE '".$Y."-".$M."-".$D."' ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllByRange($Y,$M,$D,$Y1, $M1,$D1)
		{
			$sql = "SELECT * FROM phieuchi WHERE ngaylap >= '".$Y."-".$M."-".$D."' AND ngaylap <= '".$Y1."-".$M1."-".$D1."'  ORDER BY ngaylap DESC;";
			$rs = mysql_query($sql);
			return $rs;
		}
		public function save()
		{
			$sql = "SELECT max(maso) as mahdmax FROM phieuchi";
			$rs = mysql_query($sql);
			if(!$rs)
				$this->mahd = 1;
			$r = mysql_fetch_array($rs);
			$this->mahd = $r['mahdmax'] + 1;
			$sql = "INSERT INTO phieuchi(maso,ngaylap,noidung,giatri) VALUES(".$this->mahd.",'".date('Y-m-d')."','".$this->chitiet."',".$this->giatri.");";
			$rs = mysql_query($sql);
			return $rs;
		}
		public function load()
		{
			$sql = "SELECT * FROM phieuchi WHERE maso = ".$this->mahd.";";
			$rs = mysql_query($sql);
			return $rs;
		}
	}
?>