<?php
	@session_start();
	require_once("config.php");
	error_reporting(E_ALL ^ E_DEPRECATED);
	class DataModel
	{
		protected $tbl_name;
		protected $num_col;
		protected $num_row;
		protected $has_error;
		protected $last_data;
		protected $last_query;
		public function __construct($tblname)
		{
			$this->tbl_name = $tblname;
			$this->num_col = 0;
			$this->num_row = 0;
			$this->has_error = false;
		}
		protected function _query()
		{
			$this->last_data = mysql_query($this->last_query);
			if(!$this->last_data)
				$this->has_error = true;
			else
			{
				$this->has_error = false;
				$this->num_row = mysql_num_rows($this->last_data);
				$this->num_col = mysql_num_fields($this->last_data);
			}
			return $this->last_data;
		}
		protected function _update()
		{
			$this->last_data = mysql_query($this->last_query);
			if(!$this->last_data)
				$this->has_error = true;
			else
				$this->has_error = false;
		}
		public function selectAll($cond = "")
		{
			$this->last_query = "SELECT * FROM ".$this->tbl_name." $cond";
			return $this->_query();
		}
		public function selectSome($start,$limit,$cond = "")
		{
			$this->last_query = "SELECT * FROM ".$this->tbl_name." $cond LIMIT ".$start.",".$limit;
			return $this->_query();
		}
		public function insert($val)
		{
			$this->last_query = "INSERT INTO ".$this->tbl_name." VALUES($val)";
			$this->_update();
			if($this->has_error)
				return 0;
			else
				return mysql_affected_rows();
		}
		public function update($val,$cond = "")
		{
			$this->last_query = "UPDATE ".$this->tbl_name." SET $val $cond";
			$this->_update();
			if($this->has_error)
				return 0;
			else
				return mysql_affected_rows();
		}
		public function delete($cond = "")
		{
			$this->last_query = "DELETE FROM ".$this->tbl_name;
			if($cond != "")
				$this->last_query .= " WHERE $cond";
			$this->_update();
			if($this->has_error)
				return 0;
			else
				return mysql_affected_rows();
		}
	}
?>