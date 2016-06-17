<?php

class IndexAction extends CommonAction
{
	
	function _initialize() {
		parent::_initialize ();
	}
	
	public function index() {
		unset($_SESSION['account_id']);
		$this->display();
	}
	
	public function newAccount() {
		unset($_SESSION['account_id']);
		$this->assign('account', null);
		$this->display('left');
	}
	
	public function left() {
		
		$this->display();
	}
	
	public function main() {
		$accountList = $this->accountDao->order('id desc')->select();
		$searchkey = $_GET['searchkey'];
		if( !empty($searchkey) ) {
			$where['_string'] = "website_name like '%$searchkey%' OR contract_number like '%$searchkey%' OR url like '%$searchkey%'";
		}
		$dd = array(
		    0=>array(
			  'id' => '231',
			  'is_publish' => '1',
			)
		);
		//$this->assign('accountList', $this->_page($where));
		$this->assign('accountList', $dd);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 探针模式
	 +----------------------------------------------------------
	 */
	public function checkEnv() {
		load('pointer',THINK_PATH.'/Tpl/Autoindex');//载入探针函数
		$env_table = check_env();//根据当前函数获取当前环境
		echo $env_table;
	}

}
?>