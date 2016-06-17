<?php

/**
 * 
 * 招聘管理控制器
 * @author uclnn
 *
 */
class JobAction extends AdminAction {
	
	function _initialize() {
		parent::_initialize ();
		$this->setModel('Job');
		$this->assign('c_root', $this->c_root = 27);
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
			
			if( $alias=='position' ) {
				$this->position();exit;
			}
				
			$this->_dataPage($categoryDao, $cid, $where);
				
			$this->display ();
		}
	}
	
	//职位分类
	public function position() {	
		$_SESSION [C('USER_AUTH_KEY')]['backUrl'] = __APP__.'/Admin/Job/position';//返回列表连接
		$categoryDao = M('Category');
		$_GET['pid'] = $this->c_root;
		$this->assign('c_root', $this->c_root);
		$this->assign('titleText', '职位');
		
		$this->_category();
	}
	
	//单页添加
	public function saveOne() {
		$this->_saveOne($_POST);
	}
	
	public function edit(){
		$this->_edit();
		$this->display ();
	}
	
	public function add(){
		$data = $_POST;
		$this->_add2($data);
	}

	public function update(){
		$this->_update2($_POST);
	}
	
	public function delete(){
		try {
			$ids = $_POST ["ids"];
			$count = count ( $ids );
			for($i = 0; $i < $count; $i ++) {
				$numAndId = explode ( ',', $ids [$i] );
				$job_id = $numAndId [1];
				$this->_deleteResume( $job_id );
				$this->modelDao->delete ( $job_id );
			}
			$this->success ( '删除成功！' );
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}
	
	public function deleteById(){
		try {
			$job_id = $_GET ['id'];
			$this->_deleteResume( $job_id );
			$this->modelDao->delete ( $job_id );
			$this->success ( '删除成功！' );
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}
	
	//批量删除简历
	private function _deleteResume( $job_id ) {
		$jrDao = M('JobResume');
		$jrDao->where(array('job_id'=>$job_id))->delete();
	}
	
	//删除简历
	public function deleteResume() {
		$jrDao = M('JobResume');
		if( $jrDao->delete($_GET['id']) ) {
			$this->success('删除简历成功！');
		} else {
			$this->error('删除简失败！');
		}
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
	function addResume(){
		$db = M('job_resume');
		//处理文档部分
    	if( !empty($_FILES['file']['name']) ) {
    		$this->uploadFile();
    	}
		//记录在某个字段
		$_POST['file'] = md5($_POST['file']);
		$_POST['create_time'] = time();
		if( $db->add($_POST) ) {
			$this->success('提交简历成功！');
		} else {
			$this->error('由于网络原因导致提交失败！');
		}
		
	}
	 private function uploadFile() {
    	$uploaddir = $this->upload_root_path.'files/resume/';
    	import("ORG.Util.UploadFile");
    	$upload = new UploadFile();
    	$upload->maxSize = 3292200;
    	$upload->allowExts = explode(',', 'doc,docx');
    	$upload->savePath = $uploaddir;
    	$upload->saveRule = uniqid;
    	if (!$upload->upload()) {
    		$this->error($upload->getErrorMsg());
    	} else {
    		$uploadList = $upload->getUploadFileInfo();
    		$_POST['file'] = $uploadList[0]['savename'];
    	}
    }
}
?>