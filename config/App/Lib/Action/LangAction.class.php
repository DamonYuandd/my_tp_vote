<?php

class LangAction extends CommonAction
{
	
	function _initialize() {
		parent::_initialize ();
	}
	
	public function index() {
		$this->display();
	}
	
	public function edit() {
		$id = $_GET['id'];
		if( !empty($id) ) {
			$obj = $this->categoryDao->find($id);
			$this->assign('obj', $obj);
		}
		$this->display();
	}
	
	public function save() {
		$data = $_POST;
		if (!isset($data['is_publish'])) {
			$data['is_publish'] = 0;
		}
		if (!isset($data['is_default'])) {
			$data['is_default'] = 0;
		} else {
			$_SESSION['c_lang'] = $data['alias'];
			$this->categoryDao->where(array('pid'=>$data['pid']))->setField('is_default',0);
		}
		if( empty($data['id'])) {
			$result = $this->categoryDao->add($data);
			if( $result!==false ) {
				exit('success_add');
			} else {
				exit('error_add');
			}
		} else {
			$result = $this->categoryDao->save($data);
			if( $result!==false ) {
				exit('success_update');
			} else {
				exit('error_update');
			}
		}
	}
	
	public function getAccountLangs() {
		$langs = $_GET['langs'];
		$id = $_GET['id'];
		if( !empty($id) && !empty($langs)) {
			$result = $this->accountDao->setField($_GET['field'], implode(',', $langs), array('id'=>$id));
			if( $result!==false ) {
				exit(json_encode(array('result'=>'success')));
			} else {
				exit(json_encode(array('result'=>'error')));
			}
		} else {
			exit(json_encode(array('result'=>'error')));
		}
	}
	
	public function selectLang() {
		$langList = $this->_getLangList();
		if( $this->isAjax() ) {
			return $langList;
		} else {
			exit(json_encode(array('list'=>$langList)));
		}
	}
	
	public function checkedDefault(){
		$alias = $_GET['alias'];
		$result = $this->accountDao->where(array('id'=>$_SESSION['account_id']))->setField($_GET['field'], $alias);
		if( $result!==false ) {
			exit('success_update');
		} else {
			exit('error_update');
		}
	}
	
	public function delete() {
		$id = $_GET['id'];
		if( !empty($id) ) {
			$result = $this->categoryDao->where(array('is_delete'=>1,'id'=>$id))->delete();
			if( $result!==false ) {
				exit('success_delete');
			} else {
				exit('error_delete');
			}
		}
	}
	
}
?>