<?php
/**
 * 
 * 留言管理控制器
 * @author pc
 *
 */
class GuestbookAction extends AdminAction {
	
	private $model;

	function _initialize() {
		parent::_initialize ();
		$this->setModel('Guestbook');
	}
	
	public function index() {	
		$rowpage = $_REQUEST['rowpage'];			
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
			
			$this->_dataPage($categoryDao, null, $where);
			$this->display ();
		}
	}
	
	//留言分类
	public function category( $cid ) {
		$_SESSION['category_action'] = 'Guestbook';
		$_SESSION [C('USER_AUTH_KEY')]['backUrl'] = __APP__.'/Admin/Goods/category';//返回列表连接
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2());
		$this->_category();
	}

	public function add()
	{
		if(!empty($_POST['reply'])){
			$_POST['reply_time'] = time(); //插入回复时间
		}
		$this->_add2($_POST);
	}

	public function update()
	{
		if(!empty($_POST['reply'])){
			$_POST['reply_time'] = time(); //插入回复时间
		}

		$this->_update($_POST);
	}

	public function edit() {
		$this->_edit();
		$this->display ();
	}
	
	public function move()
	{
		$this->_move();
		// $this->display();
	}

	public function deleteById()
	{
		$this->_deleteById();
		// $this->display();
	}

	public function mark_read(){
		$guestbookDao = M('Guestbook');
		$guestbookDao->find($_GET['id']);
		$guestbookDao->read = 1;
		$guestbookDao->save();
		exit;
	}

	public function isPublish() {
		$this->_updateField('is_publish');
	}

	public function delete() {
		if( isset( $_GET['id'] ) ) {
			$this->_deleteById($_GET['id']);
		} else {
			$this->_delete();
		}
	}


	//状态查询
	public function query()
	{
		$rowpage = $_REQUEST['rowpage'];
		$searchKey = $_REQUEST['searchKey'];
		//$where['lang'] = $this->lang;

		/* if(!empty($_GET['cid'])){
			$cid = addslashes($_GET['cid']);		
			$category_filter = "`category_id` = $cid AND ";
		} else {
			$category_filter = '';
		} */
		
		if ($_GET['status'] === 'reply') { //是否已回复
			if($_GET['val'] === '0'){				
				$where['_string'] = "hardware='".$_SESSION['hardware']."' AND ($category_filter  ISNULL(`reply`) OR `reply` = '') ";
			} else if($_GET['val'] === '1') {
				$where['_string'] = "hardware='".$_SESSION['hardware']."' AND ($category_filter !ISNULL(`reply`) OR `reply` != '') ";
			}
		} else if ($_GET['status'] === 'varify'){ //是否已审核
			$val = addslashes($_GET['val']);
			$where['_string'] =  "hardware='".$_SESSION['hardware']."' AND $category_filter `is_publish` = $val";
		}

		$rowpage = empty($rowpage)?10:$rowpage;
		$this->assign('dataList', $this->page($where, $rowpage));
		$this->assign('rowpage', $rowpage);

		$this->display('index');
	}
	function addGuest(){
		$db = M('Guestbook');
		$_POST['lang'] = L('language');
		$_POST['create_time'] = time();
		if(!$_POST['linkman'] || !$_POST['title'] || !$_POST['content']) {
			$this->error ( '必须填写完整内容' );
		}
		if($db->add($_POST)) {
			$this->success ( '留言成功！' );
		} else {
			$this->error ( '可能网络原因导致添加失败' );
		}
	}
}
