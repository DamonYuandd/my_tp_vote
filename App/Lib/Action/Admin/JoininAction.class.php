<?php
/**
 * 
 * 在线加盟管理控制器
 * @author uclnn
 *
 */
class JoininAction extends AdminAction {	
	
	function _initialize() {
		parent::_initialize ();		
		$this->setModel('Joinin');
		$this->assign('c_root', $this->c_root = 25);
	}
	
	public function index() {
		$cid = $_REQUEST['cid'];
		if( !empty($cid) ) {
			
			$categoryDao = D('Admin.Category');
			$category = $categoryDao->field('alias,tpl_one')->where(array('id'=>$cid))->find();
			$alias = $category['alias'];
			$tpl_one = $category['tpl_one'];
			
			if( $tpl_one=='auto' ) { //设置呈现样式为“自动”会自动选择下一级的第一个分类
				$this->goToCategoryFirst( $cid );
			} elseif( $tpl_one=='one' ) {
				$this->_oneContent($cid); //单页显示方式
			}
			if( $alias=='Join/apply' ) {
				$this->joinin_apply();exit;
			}
			
			$this->_dataPage($categoryDao, $cid, $where);
			
			$this->display ();
		}
	}
	
	public function edit(){
		$_GET['root'] = 25;
		$data['read'] = 1;
		$this->modelDao->where(array('id'=>'1'))->save($data);
		$this->_edit();
		$this->display ();
	}
	
	public function add(){
		
		$this->_add2($_POST);
	}

	public function update(){
		$this->_update2($_POST);
	}
	
	//单页添加
	public function saveOne() {
		$this->_imgUploads('news');
		$this->_saveOne($_POST);
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

	public function joinin_apply()
	{
		$searchKey = $_REQUEST['searchKey'];
		if( !empty($searchKey) && $searchKey!='请输入关键字' ) {
			$where['_string'] = "company_name like '%$searchKey%' OR linkman like '%$searchKey%'";
		}
		$rowpage = empty($rowpage)?10:$rowpage;
		$this->assign('dataList', $this->page($where, $rowpage));
		$this->assign('rowpage', $rowpage);
		$this->assign('searchKey', empty($searchKey)?'请输入关键字':$searchKey);
		$this->display ();
	}
	function addJoin(){
		$db = M('joinin');
		$_POST['lang'] = L('language');
		$_POST['create_time'] = time();
		if(!$_POST['company_name'] || !$_POST['linkman'] || !$_POST['address'] || !$_POST['tel'] || !$_POST['content'])
		{
			$this->error ( '必须填写内容！' );
		}
		if($db->add($_POST))
		{
			$this->success ( '加盟成功！' );

		}else
		{
			$this->error ( '可能网络原因导致失败，请重试' );
		}
	}
}
?>