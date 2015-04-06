<?php
	@session_start();
	require_once("config.php");
	require_once("datamodel.php");
	error_reporting(E_ALL ^ E_DEPRECATED);
	class user extends  DataModel
	{
		private $username;
		private $password;
		public $orguname;
		public function __construct($u = '',$p = '')
		{
			parent::__construct("user");
			$this->username = $u;
			$this->password = $p;
		}
		public function updatePassword()
		{
			$val = "password = '".md5($this->password)."'";
			$cond = "username = '".$this->username."'";
			return $this->update($val,$cond);
		}
		public function updateStatus($newval)
		{
			$val = "isValid = $newval";
			$cond = "username = '".$this->username."'";
			return $this->update($val,$cond);
		}
		public static function loadAll($valid = 1)
		{
			$sql = "SELECT * FROM user WHERE isValid = ".$valid." ORDER BY username DESC";
			return mysql_query($sql);
		}
		public function login()
		{
			$cond = "WHERE username = '".$this->username."' AND password = '".md5($this->password)."'";
			$this->selectAll($cond);
			if(!$this->has_error && $this->num_row == 1)
			{
				$r = mysql_fetch_array($this->last_data);
				$this->orguname = $r['username'];
				$_SESSION['uname'] = $this->username;
				return true;
			}
			return false;
		}
		public function changePassword()
		{
			$sql = "UPDATE user SET password = ".md5($this->password)." WHERE username = ".$this->username;
			mysql_query($sql);
		}
		public function addUser()
		{
			$sql = "INSERT INTO user VALUES('".$this->username."','".md5($this->password)."',1);";
			$rs = mysql_query($sql);
			if($rs)
				return true;
			else
				return false;

		}
	}
?>	