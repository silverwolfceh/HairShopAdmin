<?php
	@session_start();
	require_once("config.php");
	error_reporting(E_ALL ^ E_DEPRECATED);
	class user
	{
		private $username;
		private $password;
		public function __construct($u = '',$p = '')
		{
			$this->username = $u;
			$this->password = $p;
		}
		public function updatePassword()
		{
			$sql = "UPDATE user SET password = '".md5($this->password)."' WHERE username = '".$this->username."';";
			return mysql_query($sql);
		}
		public static function loadAll()
		{
			$sql = "SELECT * FROM user";
			return mysql_query($sql);
		}
		public function login()
		{
			$sql = "SELECT * FROM user WHERE username = '".$this->username."' AND password = '".md5($this->password)."'";
			//echo $sql;
			$rs = mysql_query($sql);
			if($rs && mysql_num_rows($rs) == 1)
			{
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
			$sql = "INSERT INTO user VALUES('".$this->username."','".md5($this->password)."');";
			$rs = mysql_query($sql);
			if($rs)
				return true;
			else
				return false;

		}
	}
?>	