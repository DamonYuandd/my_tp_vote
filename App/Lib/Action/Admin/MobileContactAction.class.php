<?php

/**
 *
 * 手机联系方式
 * @author uclnn
 *
 */
class MobileContactAction extends AdminAction
{

	function _initialize() {
		parent::_initialize ();
		$this->setModel('MobileContact');
		$this->assign('nav_site', '网站后台');
	}

	public function index() {
		$this->assign('dataList', $this->modelDao->order('lang desc')->select());
		$this->display ();
	}

	//编辑手机网站信息
	public function edit() {
	//	$this->_edit();
		$this->assign('obj',$this->editContact());
		$this->display ();
	}
	public function editContact(){
		$db = M('MobileContact');
		if($_GET['lang']){
			$lang = $_GET['lang'];
		}else{
			$lang = 'zh-cn';
		}
		$obj =  $db->where(array('category_id' => $_GET['cid'],'lang' => $lang,'is_publish' => 1))->find();
		return $obj;
	}
	public function add(){
		//$_POST['content_type'] = $this->getContentType($_POST['content']);
		$db = M('MobileContact');
		if ($db->add($_POST)) {
			$this->success ( '添加成功！' );
		}else{
			$this->error ( '添加失败！' );
		}
		//$this->_add2($_POST);
	}
	
	public function delete(){
		if( isset( $_GET['id'] ) ) {
			$this->_deleteById();
		} else {
			$this->_delete();
		}
	}
	
	public function update(){
		//$_POST['content_type'] = $this->getContentType($_POST['content']);
		$this->_update2($_POST);
	}

	public function isPublish() {
		$this->_updateField('is_publish');
	}

	public function isHome() {
		$this->_updateField('is_home');
	}
	
	public function isContact() {
		$this->_updateField('is_contact');
	}

	public function ordernum(){
		$this->_ordernum();
	}

	//返回内容类型,如：abc@huyi.cn格式返回 email字符串
	private function getContentType( $str ) {

		if(ereg("^http(s)*://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$", $str)){
			return 'http';
		}
		if(ereg("^([a-z0-9_]|\-|\.)+@(([a-z0-9_]|\-)+\.)+[a-z]{2,4}$", $str)){
			return 'email';
		}
		
		if(preg_match( '/^\+?\d{11}$/', $str )){
			return 'mobile';
		}
		if(preg_match('/^[0-9]{3,4}-[0-9]{7,8}$/',$str)){
			return 'phone';
		}
		return '';
	}
}
?>