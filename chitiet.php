<?php
	require_once("config.php");
	class chitiet
	{
		private $mahd;
		private $masp;
		private $price;
		private $chietkhau;
		public function __construct($mahd)
		{
			$this->mahd = $mahd;
		}
		public function addItem($masp,$price,$chietkhau)
		{
			$this->masp = $masp;
			$this->price = $price;
			$this->chietkhau = $chietkhau;
		}
		public function load()
		{
			$sql = "SELECT * FROM chitiet WHERE mahd = ".$this->mahd;
			$rs = mysql_query($sql);
			return $rs;
		}
		public function save()
		{
			$sql = "INSERT INTO chitiet VALUE(".$this->mahd.",".$this->masp.",".$this->price.",".$this->chietkhau.")";
			mysql_query($sql);
		}
		public function delete()
		{
			$sql = "DELETE FROM chitiet WHERE mahd = ".$this->mahd." AND masp = ".$this->masp.";";
			mysql_query($sql);
		}
		public function deleteAll()
		{
			$sql = "DELETE FROM chitiet WHERE mahd = ".$this->mahd;
			mysql_query($sql);
		}
		public function getTotal()
		{
			$sql = "SELECT sum(gia) as total FROM chitiet WHERE mahd = ".$this->mahd;
			$rs = mysql_query($sql);
			if(!$rs)
				return 0;
			$r = mysql_fetch_array($rs);
			return $r['total'];
		}
		public function getChietKhau()
		{
			$sql = "SELECT sum(chietkhau) as total FROM chitiet WHERE mahd = ".$this->mahd;
			$rs = mysql_query($sql);
			if(!$rs)
				return 0;
			$r = mysql_fetch_array($rs);
			return $r['total'];
		}
	}
?>