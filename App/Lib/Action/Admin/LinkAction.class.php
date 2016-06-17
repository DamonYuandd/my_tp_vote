<?php
/**
 * 
 * 友情链接管理控制器
 * @author uclnn
 *
 */
class LinkAction extends AdminAction {
	
	function _initialize() {
		parent::_initialize ();
		$this->c_root = 24;
		$this->setModel('Link');
		$this->assign('c_root', $this->c_root);
	}
	
	public function index() {
		$cid = $_REQUEST['cid'];
		if( !empty( $cid ) ) {
			
			$categoryDao = D('Admin.Category');
			$category = $categoryDao->field('alias,tpl_one')->where(array('id'=>$cid))->find();
			$alias = $category['alias'];
			$tpl_one = $category['tpl_one'];
				
			if( $tpl_one=='auto' ) { //设置呈现样式为“自动”会自动选择下一级的第一个分类
				$this->goToCategoryFirst( $cid );
			} elseif( $tpl_one=='one' ) {
				$this->_oneContent($cid); //单页显示方式
			}
				
			if( $alias=='link_category' ) {
				$this->linkCategory( $cid );exit;
			}
				
			$this->_dataPage($categoryDao, $cid, $where);
				
			$this->display ();
		}
	}
	
	public function linkCategory($cid) {
		$_SESSION [C('USER_AUTH_KEY')]['backUrl'] = __APP__.'/Admin/Link/linkCategory/cid/'.$cid;//返回列表连接
		$_GET['pid'] = 24;
		$this->assign('c_root', 24);
		$this->assign('titleText', '分类');
		
		$this->_category();
	}
	
	public function edit(){
		$this->_edit();
		$this->display ();
	}
	
	public function add(){
		$this->_imgUploads('link');
		$this->_add2($_POST);
	}

	public function update(){
		$this->_imgUploads('link');
		$this->_update2($_POST);
	}
	
	public function delete(){
		$this->_delete();
	}
	
	public function deleteById(){
		$this->_deleteById();
	}
	
	//删除封面
	public function deleteImage() {
		exit($this->_deleteImage($this->upload_root_path.'images/link/'));
	}
	
	public function ordernum(){
		$this->_ordernum();
	}
	
	public function move(){
		$this->_move();
	}
	
	public function copy() {
		$this->_copy();
	}
	
	public function isPublish() {
		$this->_updateField('is_publish');
	}
	
	public function isHome() {
		$this->_updateField('is_home');
	}
	
	public function isTop() {
		$this->_updateField('is_top');
	}

}
?>