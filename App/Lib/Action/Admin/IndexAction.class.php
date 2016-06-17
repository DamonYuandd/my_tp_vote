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
	
}
?>