<?php
/**
 * 
 * 产品控制器
 * @author uclnn
 *
 */
class GoodsAction extends AdminAction {
	function _initialize() {
		parent::_initialize ();
		$this->setModel('Goods');
	}
	
	public function index() {
		$cid = $_REQUEST['cid'];
		$rowpage = $_REQUEST['rowpage'];		

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

			if( $alias=='goods_one_content' ) {
				$this->goToCategoryFirst( $cid );
			}

			$this->_dataPage($categoryDao, $cid, $where);
			
			$this->display ();
		}
	}
	
	public function isHomeList() {
		$categoryDao = D('Admin.Category');
		$where['is_home'] = 1;
		$this->_dataPage( $categoryDao, $_SESSION['c_root'], $where );
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2().' > 首页');
		$this->display ('index');
	}
	
	public function isTopList() {
		$categoryDao = D('Admin.Category');
		$where['is_top'] = 1;
		$this->_dataPage( $categoryDao, $_SESSION['c_root'], $where );
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2().' > 置顶');
		$this->display ('index');
	}
	
	public function isPublish1List() {
		$categoryDao = D('Admin.Category');
		$where['is_publish'] = 1;
		$this->_dataPage( $categoryDao, $_SESSION['c_root'], $where );
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2().' > 已发布');
		$this->display ('index');
	}
	
	public function isPublish0List() {
		$categoryDao = D('Admin.Category');
		$where['is_publish'] = 0;
		$this->_dataPage( $categoryDao, $_SESSION['c_root'], $where );
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2().' > 未发布');
		$this->display ('index');
	}
	
	public function upfile(){
		
		$dir = $this->upload_root_path.'images/product/';
		$this->_upload2($dir);
	}
	
	//产品咨价
	public function inquire() {
		$goods_inquire = A('Admin.GoodsInquire');
		$goods_inquire->index();
		exit();
	}
	
	//商品分类
	public function category() {
// 		$cid = $_GET['id'];
// 		$_SESSION [C('USER_AUTH_KEY')]['backUrl'] = __APP__.'/Admin/Goods/category';//返回列表连接
		$_SESSION['category_action'] = 'Goods';
		$categoryDao = D('Admin.Category');
		//$this->assign('title', $categoryDao->getTitle($_SESSION['c_root']));
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2());
		$this->_category($where);
	}
	
	
	public function upload(){
				
		$this->display ();
	}
	
	//单页添加
	public function saveOne() {
		$this->_imgUploads('news');
		$this->_saveOne($_POST);
	}
	
	public function edit(){
		$this->_edit();
		$gpDao = M('GoodsPhoto');
		$photos = $gpDao->where(array('goods_id'=>$_GET['id']))->select();
		$this->assign('photos', $photos);
		$this->display ();
	}
	
	public function add(){
		try {
			$data = $this->_processData($_POST);
			$this->setJumpUrl( 'Goods/index/cid/'.$_SESSION['sidemenu_cid'].'/lang/'.$_SESSION['sidemenu_lang'] );
			if ($this->modelDao->add ( $data )!==false) {
				
				//上传产品图片
				$good_id = $this->modelDao->getLastInsID();
				$this->uploadProduct($good_id);
				$gpDao = M('GoodsPhoto');
				$photo = $gpDao->where(array('goods_id'=>$good_id))->order('ordernum desc')->find();
				$this->modelDao->where(array('id'=>$good_id))->setField('image',$photo['image']);
				
				//手机同步产品图片
				if (isset($data['synch_mobile'])) {
					$data['image'] = $photo['image'];
					$data['category_id'] = $this->_getModileCategoryId($data);
					$synch_msg = $this->_synchMobileList($data);
					$good_insid = $this->modelDao->getLastInsID();
					$gpDao = M('GoodsPhoto');
					$gphotos = $gpDao->where(array('goods_id'=>$good_id))->order('ordernum desc')->select();
					$gp_count = count($gphotos);
					for($i=0;$i<$gp_count;$i++){
						$gphoto = $gphotos[$i];
						unset($gphoto['id']);
						$gphoto['goods_id'] = $good_insid;
						$gpDao->add($gphoto);
					}
				}
				
				$this->success ( '添加成功！'.$synch_msg, $ajax );
			} else {
				$this->error ( '添加失败！', $ajax );
			}
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}

	public function update(){
		$this->setJumpUrl( 'Goods/index/cid/'.$_SESSION['sidemenu_cid'].'/lang/'.$_SESSION['sidemenu_lang'] );
		$this->uploadProduct($_POST['id']);
		$this->_update2($_POST);
	}
	
	//上传产品图片
	private function uploadProduct($good_id) {
		$gpDao = M('GoodsPhoto');
		$photoList = $gpDao->where(array('goods_id'=>$good_id))->order('ordernum desc')->select();
		for($i=0; $i < 4; $i++) {
			if ( !empty($_FILES['image'.$i]) ) {
				$image = $this->_img_upload('product', 'image'.$i);
				if( empty($photoList[$i]) && !empty($image) ) {
					$gpDao->add(array('goods_id'=>$good_id,'image'=>$image));
				} elseif( !empty($image) ) {
					$gpDao->where(array('id'=>$photoList[$i]['id']))->setField('image', $image);
				}
			} else {
				continue;
			}
		}
	}
	
	//设置默认图片
	public function photoDef() {
		$id = $_GET['id'];
		$image = $_GET['image'];
		if( !empty($id) && !empty($image) ) {
			$goodsDao = M('Goods');
			$result = $goodsDao->where(array('id'=>$id))->setField('image',$image);
		}
		exit($result);
	}
	
	public function delete(){
		$this->_delete();
	}
	
	public function deleteById(){
		$this->_deleteById();
	}
	
	//删除封面
	public function deleteImage() {
		$gp_id = $_GET['gp_id'];
		if( !empty($gp_id) ) {
			$gpDao = M('GoodsPhoto');
			$image = $gpDao->where(array('id'=>$gp_id))->getField('image');
			$result = $gpDao->delete($gp_id);
			if( $result!==false ) {
				$path = $this->upload_root_path.'images/product/';
				unlink($path.'s_'.$image);
				unlink($path.'m_'.$image);
				exit(true);
			} else {
				exit(false);
			}
		} else {
			exit(false);
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
	
}
?>