<?php
/**
 * 
 * 会员管理控制器
 * @author uclnn
 *
 */
class MemberAction extends AdminAction {
	
	function _initialize() {
		parent::_initialize ();
		$this->setModel('Member');
		$this->assign('c_root', $this->c_root=29);
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
			
			$this->_dataPage($categoryDao, $cid, $where);
				
			$this->display ();
		}
	}
	
	public function edit(){
		$member = $this->modelDao->find($_GET['id']);
		$this->assign('obj', $member);
		$this->display ();
	}

	public function delete(){
		$this->_delete();
	}
	
	public function deleteById(){
		$this->_deleteById();
	}
	
	public function ordernum(){
		$this->_ordernum();
	}
	
	public function move(){
		$this->_move();
	}

	public function isPublish() {
		$this->_updateField('is_publish');
	}

}
?>