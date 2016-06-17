<?php

/**
 *
 * 手机网站
 * @author uclnn
 *
 */
class MobileAction extends AdminAction
{

	function _initialize() {
		parent::_initialize ();
		//$this->setModel('Mobile');
		$this->assign('nav_site', '网站后台');
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

			//推荐管理
			if( $alias=='MobileBase' ) {
				$this->base();exit;
			} elseif( $alias=='MobileSEO' ) {
				$this->seo();exit;
			} elseif( $alias=='MobileDomain' ) {
				$this->domain();exit;
			} elseif( $alias=='MobileTheme' ) {
				$systemA = A('Admin.System');
				$systemA->themeMobile(); exit;
			}

			//if($this->lang!='all') $where['lang']=$this->lang;
			$this->_dataPage($categoryDao, $cid, $where);

			$this->display ();
		}
	}

	//基本设置
	public function base() {
		$systemDao = M ( 'System' );
		$this->assign ( 'system', $systemDao->where ( array ('lang' => 'mobile') )->find () );
		$this->display ('base');
	}

// 	//手机主题
// 	public function theme() {
// 		$systemDao = M ( 'System' );
// 		$this->assign ( 'system', $systemDao->where ( array ('lang' => 'mobile') )->find () );
// 		$this->display ('theme');
// 	}

	//全局SEO
	public function seo() {
		$systemDao = M ( 'System' );
		$this->assign ( 'system', $systemDao->where ( array ('lang' => 'mobile') )->find () );
		$this->display ('seo');
	}

	//多域名
	public function domain() {
		$systemDao = M ( 'System' );
		$domain = M('domain');
		$this->assign( 'domain' , $domain->select());
		$this->assign ( 'system', $systemDao->where ( array ('lang' => 'mobile') )->find () );
		$this->display ('Mobile/domain');
	}

	public function saveBase() {
		try {
			$image = $this->_img_upload('mobile','image',false);
			if( !empty($image) ) {
				$_POST['image'] = $image;
			}
			$app_logo = $this->_img_upload('mobile','app_logo',false);
			if( !empty($image) ) {
				$_POST['app_logo'] = $app_logo;
			}
			
			$this->setModel ( 'System' );
			$where ['lang'] = $_POST ['lang'];
			$count = $this->modelDao->where ( $where )->count ();
			if ($count > 0) {
				$this->modelDao->where ( $where )->save ( $_POST );
				$this->success ( '修改成功！' );
			} else {
				$this->_add ( $_POST );
			}
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}

	public function saveSEO() {
		try {
			$this->setModel ( 'System' );
			$where ['lang'] = $_POST ['lang'];
			$count = $this->modelDao->where ( $where )->count ();
			if ($count > 0) {
				$this->modelDao->where ( $where )->save ( $_POST );
				$this->success ( '修改成功！' );
			} else {
				$this->_add ( $_POST );
			}
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}

	function saveDomain(){
		$db = M('domain');
		if(!$_POST['id'])
		{
			try{
				$bool = $db->add($_POST);
				if($bool!==false)
				{
					$this->success ( '添加成功！' );
				}else {
					$this->error ( '可能网络原因导致添加失败，请重试' );
				}

			}catch ( Exception $e){
				$this->error('异常：'.$e->getMessage() );
			};
		}else
		{	$bool = $db->where('id ='.$_POST['id'])->save($_POST);
		if($bool!==false)
		{
			$this->success ( '修改成功！' );
		}else
		{dump($db->getLastSql());
		$this->error ( '可能网络原因导致添加失败，请重试' );
		}
		}
	}

	function deleteDomain(){
		$db = M('domain');
		try{
			if($db->where('id ='.$_GET['id'])->delete())
			{
				$this->success ( '删除成功！' );
			}else
			{
				$this->error ( '可能网络原因导致添加失败，请重试' );
			}
		}catch( Exception $e)
		{
			$this->error('异常：'.$e->getMessage() );
		}
	}

	function edit_domain(){
		$this->assign('info',$this->showDomain($_GET['id']));
		$this->display();
	}
	public	function showDomain($id){
		$db = M('domain');
		$result = $db->where('id ='.$id)->find();
		return $result;
	}

	//删除Logo
	public function deleteImage() {
		$this->setModel('System');
		exit($this->_deleteImage($this->upload_root_path.'images/mobile/'));
	}
	
	//删除APP Logo
	public function deleteAppLogo() {
		$this->setModel('System');
		exit($this->_deleteImage($this->upload_root_path.'images/mobile/','app_logo'));
	}

}
?>