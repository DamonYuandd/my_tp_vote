<?php
/**
 * 
 * 产品控制器
 * @author uclnn
 *
 */
class GoodsInquireAction extends AdminAction {
	function _initialize() {
		parent::_initialize ();
		$this->list_pid = 20;		
		$this->setModel('GoodsInquire');
	}
	
	public function index() {
		$this->assign('nav_site', '网站后台 > '.$this->_getDefLangTitle2());
		
		$cid = $_REQUEST['cid'];

		$rowpage = empty($_GET['rowpage'])?10:$_GET['rowpage'];
		
		$status = addslashes($_GET['status']);
		if( !empty($_GET['status']) ) {
			$where['_string'] = " `status` = '$status'";
		}
		
		$dataList = $this->page($where, $rowpage);
		$goodsDao = M('Goods');
		foreach ($dataList as &$inquire) {
			$goods = $goodsDao->where(array('id'=>$inquire['goods_id']))->find();
			$inquire['goods_title'] = $goods['title'];
			$inquire['lang'] = $goods['lang'];
		}
		unset($inquire);
		
		$this->assign('dataList', $dataList );
		$this->assign('rowpage', $rowpage);
		$this->assign('searchKey', empty($searchKey)?'请输入关键字':$searchKey);
		$this->display('GoodsInquire/goods_inquire');
	}
	
	public function delete(){
		$this->_delete();
	}
	
	public function deleteById(){
		$this->_deleteById();
	}

	public function set_inquire_status() {
		$id = $_GET['id'];

		//状态代码 0 未处理 1 已跟进 2 忽略		
		if($_GET['status'] === '0'){
			$data['status'] = '未处理';
		} else if($_GET['status'] === '1') {
			$data['status'] = '已跟进';
		} else if($_GET['status'] === '2') {
			$data['status'] = '忽略';
		} else {
			exit;
		}

		$this->modelDao->where(array('id'=>$id))->save($data);
	}
}
?>