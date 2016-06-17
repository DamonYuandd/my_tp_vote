<?php
/**
 * 
 * 介绍内容控制器
 * @author uclnn
 *
 */
class ReservationAction extends AdminAction {
	
	function _initialize() {
		parent::_initialize ();
		$this->setModel('News');
	}
	
	function index(){
		$where = '';
		$st = $_GET['start_time'].' 00:00:00';
		$et = $_GET['start_time'].' 23:59:59';
		if($_GET['start_time']){
			$where = 'reservation_date >= \''.$st.'\'';
		}
		if($_GET['end_time']){
			$where .= 'and reservation_date <= \''.$et.'\'';
		}
		if($_GET['searchKey']){
			$where .= 'and phone = \''.$_GET['searchKey'].'\'';
		}
		/*分页*/
		import ( "ORG.Util.Page" );
		$count = M('reservation')->where ( $where )->count ();
		
		$page = new Page ( $count, 15 );
		$pageBar = $page->show ();
		$dataList = M('reservation')->where($where)->order('id desc')->limit ( $page->firstRow . ',' . $page->listRows )->select();
		
		$this->assign ( 'searchKey',$_GET['searchKey'] );
		$this->assign ( 'dataList',$dataList );
		$this->assign ( 'pageBar', $pageBar );
		$this->assign ( 'sort', $sort);
		$this->assign ( 'totalRows', $count );
		$this->assign ( 'rowpage', $rowpage );
		$this->display();
	}
	
	function edit(){
		$id = $_GET['id'];
		$obj = M('reservation')->where(array('id'=>$id))->find();
		
		$this->assign('obj',$obj);
		$this->display();
		
	}
	
}
?>