<?php

/**
 * 
 * 后台主框架控制器
 * @author uclnn
 *
 */
class IndexAction extends AdminAction {

	
	public function index() {
		
		$this->_assignModuleList();
		
		$categoryDao = M('Category');
		$langList = $categoryDao->where(array('alias'=>array('in',$this->custom['langs'])))->select();//选择语言
		$this->assign('langList', $langList);
		$this->display ( 'Layout:admin' );
	}

	//系统首页
	public function main() {
		$newsDao = M('News');
		$goodsDao = M('Goods');
		$guestbookDao = M('Guestbook');
		$advertDao = M('Advert');
		$linkDao = M('Link');
		$downloadDao = M('Download');
		$jobDao = M('Job');
		$jobrDao = M('JobResume');
		$memberDao = M('Member');
// 		$customDao = M('Custom');
		
		$this->assign('newsCount', $newsDao->count());
		$this->assign('goodsCount', $goodsDao->count());
		$this->assign('guestbookCount', $guestbookDao->count());
		$this->assign('guestbookCountRead0', $guestbookDao->where(array('read'=>0))->count());
		$this->assign('advertCount', $advertDao->count());
		$this->assign('linkCount', $linkDao->count());
		$this->assign('downloadCount', $downloadDao->count());
		$this->assign('jobCount', $jobDao->count());
		$this->assign('jobrCount', $jobrDao->count());
		$this->assign('memberCount', $memberDao->count());
		$this->assign('todayMemberCount', $memberDao->where("(FROM_UNIXTIME(create_time,'%Y-%m-%d')='" . date ( 'Y-m-d', time () ) . "')")->count());
		
		$this->_assignModuleList();
		
		$this->display ();
	}
	
	public function mainMenu() {
		exit;
	}

	public function category() {
		$this->assign('c_root', $_GET['c_root']);
		$this->display ();
	}

	//左边菜单
	public function sidemenu() {
		$pid = $_GET['pid'];
		if( !empty( $pid ) ) {
			$categoryDao = M('Category');
			$langin = $this->getSqlInLangs();
			$where['_string'] = "(is_publish=1 AND title<>'' AND hardware='".$_SESSION['hardware']."' AND lang in(".$langin.")) OR is_fixed=1";
			$dataList = $categoryDao->where( $where )->order('lang desc,ordernum desc')->select();
			Load('extend');
			$dataList = list_to_tree($dataList, 'id', 'pid','_child',$pid);
			$this->assign('module', $categoryDao->getField('module',array('id'=>$pid)));
			$this->assign('alias', $categoryDao->getField('alias',array('id'=>$pid)));
			$this->assign('dataList', $dataList);
		} else {
			$this->assign('module','System');
		}
		$this->display ();
	}
	
	//获取网站多语言SQL查询IN()参数
	protected function getSqlInLangs() {
		$categoryDao = M('Category');
		if( $_SESSION['hardware']=='pc' ) {
			$lang_pid = 3;
		} elseif( $_SESSION['hardware']=='mobile' ) {
			$lang_pid = 4;
		}
		$alias = $categoryDao->where(array('pid'=>$lang_pid,'is_publish'=>1))->field('alias')->select();
		$alias_count = count($alias);
		$langin = '';
		for ($i = 0; $i < $alias_count; $i++) {
			$langin .= "'".$alias[$i]['alias']."'";
			if( $alias_count-1 > $i ) {
				$langin .= ',';
			}
		}
		return $langin;
	}
	
	//改变当前语 言
	public function checkedLang() {
		$lang = $_GET['lang'];
		$_SESSION[C('USER_AUTH_KEY')]['lang'] = $lang;
	}
	
	//获取单个分类信息
	public function getCategory() {
		$id = $_REQUEST['id'];
		if( !empty($id) ) {
			$categoryDao = M('Category');
			$category = $categoryDao->find($id);
			if ( $this->isAjax() ) {
				exit(json_encode($category));
			} else {
				$this->assign('obj',$category);
			}
		}
	}
	
	//分类查找
	function selectCategoryByPid() {
		if( !empty($_GET['lang']) ) {
			$where['lang'] = $_GET['lang'];
		} else {
			$where['lang'] = array('in',getSqlInLangs());
		}
		if( !empty($_GET['hwe']) ) {
			$where['hardware'] = $_GET['hwe'];
		}
		if( !empty($_GET['pid']) ) {
			$where['pid'] = $_GET['pid'];
			$where['is_publish'] = 1;
			$categoryDao = M ( "Category" );
			$categoryList = $categoryDao->where ( $where )->order('hardware desc,lang desc,ordernum desc')->select ();
			$count = count($categoryList);
			for ($i = 0; $i < $count; $i++) {
				$hardware = $categoryList[$i]['hardware'];
				if($hardware=='pc') {
					$hardware_text = '电脑>';
				} elseif($hardware=='mobile') {
					$hardware_text = '手机>';
				}
				$title = $categoryDao->where(array('hardware'=>$hardware,'alias'=>$categoryList[$i]['lang']))->getField('title');
				$categoryList[$i]['title'] = '['.$hardware_text.substr($title, 0, 3).'] '.$categoryList[$i]['title'];
			}
			if( $this->isAjax() ) {
				$json ['list'] = $categoryList;
				exit(json_encode ( $json ));
			} else {
				$this->assign('categoryList', $categoryList);
			}
		}
	}

	//手机分类输出
	function selectMobileCategoryByPid() {
		if( !empty($_GET['lang']) ) {
			$where['lang'] = $_GET['lang'];
		} else {
			$where['lang'] = array('in',getSqlInLangs());
		}
		if( !empty($_GET['hwe']) ) {
			$where['hardware'] = $_GET['hwe'];
		}
		if( !empty($_GET['pid']) ) {
			$where['pid'] = $_GET['pid'];
			$where['is_publish'] = 1;
			$categoryDao = M ( "Category" );
			$categoryList = $categoryDao->where ( $where )->order('hardware desc,lang desc,ordernum desc')->select ();
			$count = count($categoryList);
			for ($i = 0; $i < $count; $i++) {
				$hardware = $categoryList[$i]['hardware'];
				$title = $categoryDao->where(array('hardware'=>$hardware,'alias'=>$categoryList[$i]['lang']))->getField('title');
				$categoryList[$i]['title'] = '['.substr($title, 0, 3).'] '.$categoryList[$i]['title'];
			}
			if( $this->isAjax() ) {
				$json ['list'] = $categoryList;
				exit(json_encode ( $json ));
			} else {
				$this->assign('categoryList', $categoryList);
			}
		}
	}
	
	//导出excel
	public function excelPort(){
		$db = $_GET['db'];
		$show = $_GET['show'];
		$listShow = C('SHOW_LIST');
		// import("ORG.PHPExcel.PHPExcel");
		import("ORG.Util.PHPExcel");
		
		$objPHPExcel = new PHPExcel();
	
		/*以下是一些设置 ，什么作者  标题啊之类的*/
		$objPHPExcel->getProperties()->setCreator("转弯的阳光")
		->setLastModifiedBy("转弯的阳光")
		->setTitle("数据EXCEL导出")
		->setSubject("数据EXCEL导出")
		->setDescription("备份数据")
		->setKeywords("excel")
		->setCategory("result file");
	
		$db = M($db);
		if($_GET['db'] == 'Member'){
			$result = $db->where(array('category_id' => $_GET['cid']))->order('ordernum desc,create_time desc')->select();
		}else{
			if ($show && $show != 0){
				$result = $db->where(array('select_show' => $show))->order('create_time desc')->select();	//查找结果
			}else{
				$result = $db->order('create_time desc')->select();	//查找结果
			}
		}
		/* echo $db->getlastsql();
		 dump($result);
		 exit;
		 */
		foreach($result as $key => $value){
			$result[$key]['user_info'] = menberInfo($value['user_id']);
		}
		/* dump($result);
		 exit; */
		if($_GET['db'] == 'Apply'){	//参展申请表
			$objPHPExcel->setActiveSheetIndex(0)
			//Excel的第A列，uid是你查出数组的键值，下面以此类推
			->setCellValue('A1', '联系人')
			->setCellValue('B1', '会员名称')
			->setCellValue('C1', '公司名称')
			->setCellValue('D1', '部门/职位')
			->setCellValue('E1', '联系电话')
			->setCellValue('F1', '联系传真')
			->setCellValue('G1', 'E-MAIL')
			->setCellValue('H1', '公司性质')
			->setCellValue('I1', '邮政编码')
			->setCellValue('J1', '公司网址')
			->setCellValue('K1', '详细地址')
			->setCellValue('L1', '标准展位')
			->setCellValue('M1', '光地')
			->setCellValue('N1', '备注留言')
			->setCellValue('O1', '申请时间')
			->setCellValue('P1', '流水号')
			->setCellValue('Q1', '参展名称')
			;
			foreach($result as $key => $v){
				$num=$key+2;
				$objPHPExcel->setActiveSheetIndex(0)
				//Excel的第A列，uid是你查出数组的键值，下面以此类推
				->setCellValue('A'.$num, $v['contact'])
				->setCellValue('B'.$num, $v['user_info']['username'])
				->setCellValue('C'.$num, $v['company'])
				->setCellValue('D'.$num, $v['post'])
				->setCellValue('E'.$num, $v['tel'])
				->setCellValue('F'.$num, $v['fax'])
				->setCellValue('G'.$num, $v['user_info']['email'])
				->setCellValue('H'.$num, $v['nature'])
				->setCellValue('I'.$num, $v['code'])
				->setCellValue('J'.$num, $v['website'])
				->setCellValue('K'.$num, $v['address'])
				->setCellValue('L'.$num, $v['standard'])
				->setCellValue('M'.$num, $v['light'])
				->setCellValue('N'.$num, $v['remark'])
				->setCellValue('O'.$num, date("Y-m-d",$v['create_time']))
				->setCellValue('P'.$num, $v['apply_id'])
				->setCellValue('Q'.$num, $listShow[$v['select_show']]['name'])
				;
			}
		}
			
		
			
			
			
			
		$objPHPExcel->getActiveSheet()->setTitle('User');
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.time().'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
}
?>