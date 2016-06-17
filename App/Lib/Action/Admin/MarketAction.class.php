<?php
/**
 * 
 * 网络营销管理控制器
 * @author uclnn
 *
 */
class MarketAction extends AdminAction {
	
	function _initialize() {
		parent::_initialize ();
		$this->setModel('Market');
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
	
	//区域管理
	public function category() {
		$_SESSION['category_action'] = 'Market';
		$_SESSION [C('USER_AUTH_KEY')]['backUrl'] = __APP__.'/Admin/Market/marketArea/cid/'.$cid;//返回列表连接
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2());
		$this->_category($where);
	}
	
	public function edit(){
		$this->_edit();
		if($_SESSION['hardware']=='mobile') {
			$db = M('Market');
			$obj = $db->where(array('category_id' => $_GET['cid'],'lang'=>$_GET['lang'],'is_publish'=>1,'hardware'=>'mobile'))->find();
			$this->assign('obj',$obj);
			$this->display ('edit_mobile');
		} else {
			$this->display ();
		}
	}
	
	//单页添加
	public function saveOne() {
		$this->setJumpUrl( 'Market/index/cid/'.$_SESSION['sidemenu_cid'].'/lang/'.$_SESSION['sidemenu_lang'] );
		$image = $this->_img_upload('market');
		if( !empty($image) ) {
			$_POST['image'] = $image;
		}
		$this->_saveOne($_POST);
	}
	
	public function add(){
		$this->setJumpUrl( 'Market/index/cid/'.$_SESSION['sidemenu_cid'].'/lang/'.$_SESSION['sidemenu_lang'] );
		$image = $this->_img_upload('market');
		if( !empty($image) ) {
			$_POST['image'] = $image;
		}
		$this->_add2($_POST);
	}

	public function update(){
		$this->setJumpUrl( 'Market/index/cid/'.$_SESSION['sidemenu_cid'].'/lang/'.$_SESSION['sidemenu_lang'] );
		$image = $this->_img_upload('market');
		if( !empty($image) ) {
			$_POST['image'] = $image;
		}
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
		exit($this->_deleteImage($this->upload_root_path.'images/market/'));
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