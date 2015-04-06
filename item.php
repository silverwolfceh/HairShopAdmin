<?php
	require_once("config.php");
	class item
	{
		private $masp;
		private $tensp;
		private $price;
		private $discount;
		public function __construct($masp = -1)
		{
			$this->masp = $masp;
		}
		public function add($tensp,$price,$discount)
		{
			$this->tensp = $tensp;
			$this->price = $price;
			$this->discount = $discount;
			$sql = "INSERT INTO product(tensp,giamacdinh,chietkhau) VALUES('".$this->tensp."',".$this->price.",".$this->discount.");";
			mysql_query($sql);
		}
		public function setId($masp)
		{
			$this->masp = $masp;
		}
		public function update($tensp,$giamacdinh = "",$chietkhau = "")
		{
			$sql = "UPDATE product SET tensp = '".$tensp."' ";
			if($giamacdinh != "")
				$sql.= ",giamacdinh =".$giamacdinh." ";
			if($chietkhau != "")
				$sql.= ",chietkhau =".$chietkhau." ";
			$sql.= "WHERE masp =".$this->masp;
			mysql_query($sql);
		}
		public function delete()
		{
			$sql = "UPDATE product SET isValid = 0 WHERE masp = ".$this->masp;
			mysql_query($sql);
		}
		public function getName($masp)
		{
			$this->masp = $masp;
			$rs = $this->loadItem();
			if(!$rs)
				return "";
			$r = mysql_fetch_array($rs);
			return $r[1];
		}
		public function loadItem()
		{
			$sql = "SELECT * FROM product WHERE masp = ".$this->masp;
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function loadAllItem()
		{
			$sql = 'SELECT * FROM product WHERE isValid = 1';
			$rs = mysql_query($sql);
			return $rs;
		}
		public static function getListOfItem($listid)
		{
			$obj = new item();
			$rs = "";
			$ids = explode(",",$listid);
			foreach($ids as $id)
			{
				$rs .= $obj->getName($id)."<br/>";
			}
			return $rs;
		}
		public static function getListOfCustomer($id)
		{
			$sql = "SELECT * FROM customer";
		}
	}
?>