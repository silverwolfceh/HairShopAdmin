<?php 
	@session_start();
	require_once("config.php");
	require_once("datamodel.php");
	error_reporting(E_ALL ^ E_DEPRECATED);
	class customer extends DataModel
	{
		private $hoten;
		private $sodt;
		private $lasttime;
		private $dsdichvu;
		public function __construct($sdt = "")
		{
			parent::__construct("customer");
			$this->sodt = $sdt;
		}
		private function isServiceExist($l,$dv)
		{
			$list = explode(",",$l);
			return in_array($dv,$list);
		}
		private function finalizeService($cur,$new)
		{
			$newlist = explode(",",$new);
			for($i=0; $i < count($newlist); $i++)
			{
				if(!$this->isServiceExist($cur,$newlist[$i]))
					$cur .= $newlist[$i].",";
			}
			return trim($cur, ",");
		}
		public static function calculateDayDiff($s2)
		{
			$now = time();
			$past = strtotime($s2);
			$datediff = $now - $past;
			return floor($datediff/(60*60*24));
		}
		public function addUpdateCustomer($sdt,$hoten = "",$dichvu = "")
		{
			$cond = "WHERE sodt = '".$sdt."'";
			$this->selectAll($cond);
			if($this->num_row != 0)
			{
				$val = "lasttime = '".date('Y-m-d')."'";
				if($hoten != "")
					$val .= ",hoten = '".$hoten."'";
				if($dichvu != "")
				{
					$r = mysql_fetch_array($this->last_data);
					$dvhientai = $r['service'];
					$val .= ",service = '".$this->finalizeService($dvhientai,$dichvu)."'";
				}
				$cond = "WHERE sodt = '".$sdt."'";
				return $this->update($val,$cond);
			}
			else
			{
				$val = "'".$sdt."','".$hoten."','".date('Y-m-d')."','".$dichvu."'";
				return $this->insert($val);
			}
		}
		public static function getAllCustomers()
		{
			$q = "SELECT * FROM customer";
			$rs = mysql_query($q);
			return $rs;
		}
	}
?>