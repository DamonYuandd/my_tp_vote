<?php
class SystemModel extends Model {

	function getModel($account){
		return $this->db(1, 'mysql://'.$account['db_user'].':'.$account['db_pwd'].'@'.$account['db_host'].':'.$account['db_port'].'/'.$account['db_name']);
	}
	
}