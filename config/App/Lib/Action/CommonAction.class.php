<?php

class CommonAction extends Action
{
	
	protected $categoryDao;
	
	protected $accountDao;
	
	protected $account;
	
	protected $isConnect;
	
	protected $account_id;
	
	protected $modelDao;
	
	function _initialize() {
		
		$this->account_id = $_SESSION['account_id'];
		$this->accountDao = M('Account');
		//$this->account = $this->_getAccount($this->account_id);
		$this->account = array(
			  'id' => 231,
			  'is_publish' => 1,
			  'langs' => null,
			  'modules' => null,
			  'def_lang' => null,
			  'begin_time' => 1358144370,
			  'end_time' => 1389680370,
			  'space_mb' => 3000,
			  'website_name' => '测试',
			  'contract_number' => C('DB_NAME'),
			  'username' => 'admin',
			  'password' => '',
			  'url' => ',172.16.9.24,',
			  'db_host' => C('DB_HOST'),
			  'db_name' => C('DB_NAME'),
			  'db_user' => C('DB_USER'),
			  'db_pwd' => C('DB_PWD'),
			  'db_port' => '3306',
			  'db_prefix' => 'y_',
			  'mobile_theme' => 'YM04001',
			  'web_theme' => 'YP17001',
			  'flow_code' => null,
			  'yy_price' => '',
			  'service_price' => '',
			  'main_domain' => '',
			  'contract_domain' => '',
			  'website_type' => null,
			  'customer_email' => null,
			  'customer_phone' => null,
			  'customer_address' => null,
			  'customer_zipcode' => null,
			  'create_source' => ''
		);
		//dump($this->account);
		//$this->isConnect = $this->_isConnect($this->account);
		$this->isConnect = true;
		if( $this->isConnect ) {
			$this->categoryDao = $this->_getAccountModel('Category',$this->account);
		}
	//	dump($this->categoryDao);
		$this->assign('account', $this->account);
	}
	
	protected function setModel($modelName) {
		$this->modelDao = D ( $modelName );
	}
	
	public function index() {
		$this->display();
	}
	
	protected function _getAccountModel( $model, $db ) {
		try{
			$model = new Model($model);
			$newModel = $model->db(1,"mysql://".$db['db_user'].":".$db['db_pwd']."@".$db['db_host'].":".$db['db_port']."/".$db['db_name']);
			if($newModel!==false) {
				return $newModel;
			} else {
				return null;
			}
		} catch (Exception $ex) {
			return null;
		}
	}
	
	protected function _getConnectModel($model, $obj) {
		try{
			$model = new Model($model);
			$newModel = $model->db(1,"mysql://".$obj['db_user'].":".$obj['db_pwd']."@".$obj['db_host'].":".$obj['db_port']."/".$obj['db_name']);
			return $newModel;
		} catch (Exception $e) {
			return null;
		}
	}
	
	protected function _getLangList() {
		$langList = $this->categoryDao->where(array('pid'=>array('in','3,4')))->order('ordernum desc')->select();
		
		return $langList;
	}
	
	protected function _getModuleList($pid,$is_publish=null) {
		$where['pid'] = $pid;
		if( !empty($is_publish) ) {
			$where['is_publish'] = $is_publish;
		}
		return $this->categoryDao->where($where)->order('ordernum desc')->select();
		// $this->categoryDao->where($where)->order('ordernum desc')->select();
	}
	
	protected function _getCheckedLangList() {
		$langList = $this->categoryDao->where(array('pid'=>3,'is_publish'=>1))->order('is_default desc,ordernum desc')->select();
		return $langList;
	}
	
	protected function _getCategoryByPidList( $id ) {
		$categoryList = $this->categoryDao->where ( array('pid'=>$id) )->order('hardware desc,lang desc,ordernum desc')->select ();
		return $categoryList;
	}
	
	protected function _getCategoryLangTitleList( $id ) {
		//$categoryList = $this->_getCategoryByPidList( $id );
		$sql = $this->getSqlHardwareLangs();
		$where['_string'] = "pid=$id AND (".$sql.")";
		$categoryList = $this->categoryDao->where ( $where )->order('hardware desc,lang desc,ordernum desc')->select ();
		//dump($this->categoryDao->getLastSql());
		$count = count($categoryList);
		for ($i = 0; $i < $count; $i++) {
			$lang = $categoryList[$i]['lang'];
			$hardware = $categoryList[$i]['hardware'];
			if( !empty($lang) ) {
				$title = $this->categoryDao->getField('title', array('alias'=>$lang));
				if($hardware=='pc') {
					$hardware = '电脑';
				} elseif($hardware=='mobile') {
					$hardware = '手机';
				}
				$lang_title = '<span style="color:#BB4141">['.$hardware.'>'.substr($title, 0, 3).']</span> ';
			} else {
				$lang_title = '';
			}
			$categoryList[$i]['title'] = $lang_title.$categoryList[$i]['title'];
		}
		return $categoryList;
	}
	
	//获取网站多语言SQL查询IN()参数
	protected function getSqlHardwareLangs() {
/* 		if( $_SESSION['hardware']=='pc' ) {
			$lang_pid = 3;
		} elseif( $_SESSION['hardware']=='mobile' ) {
			$lang_pid = 4;
		} */
		$alias = $this->categoryDao->where(array('pid'=>array('in','3,4'),'is_publish'=>1))->field('hardware,alias')->select();
		$alias_count = count($alias);
		$langin = '';
		for ($i = 0; $i < $alias_count; $i++) {
			$langin .= "(hardware='".$alias[$i]['hardware']."' AND lang='".$alias[$i]['alias']."')";
			if( $alias_count-1 > $i ) {
				$langin .= 'OR';
			}
		}
		return $langin;
	}
	
	protected function _getAccount( $id ) {
		if( empty($id) ) {
			return null;
		} else {
			return $this->accountDao->find($id);
		}
	}
	
	protected function _isConnect($account) {
		try{
			$con = $this->_getConnect($account);
			if( $con ) {
				$bool = mysql_select_db($account['db_name'],$con);
				mysql_close($con);
				return $bool;
			} else {
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	protected function _getConnect($account) {
		return mysql_connect($account['db_host'], $account['db_user'], $account['db_pwd']);
	}
	
	protected function _uploadImg($uploaddir='',$field='image',$thumb=true,$file_name=uniqid,$width=300,$height=300) {
		import("ORG.Util.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = 3292200;
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		$upload->savePath = $uploaddir;
		if( $thumb==true ) {
			$upload->thumb = true;
			$upload->imageClassPath = 'ORG.Util.Image';
			$upload->thumbPrefix = 'm_,s_';
			$upload->thumbMaxWidth = '1000,'.$width;
			$upload->thumbMaxHeight = '1000,'.$height;
			$upload->thumbRemoveOrigin = true;
		}
		$upload->saveRule = $file_name;
		if (!$upload->upload()) {
			$this->error($upload->getErrorMsg());
		} else {
			$uploadList = $upload->getUploadFileInfo();
			//import("@.ORG.Image");
			//给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
			//Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], '/ThinkPHP_2.2_Full/Examples/File/Tpl/default/Public/Images/logo2.png');
			$_POST[$field] = $uploadList[0]['savename'];
		}
	
	}
	
	//删除图片
	protected function _deleteImage( $path ) {
		$where['id'] = $_GET['id'];
		$filename = $this->modelDao->where($where)->getField('image');
		unlink($path.$filename);
		unlink($path.'s_'.$filename);
		unlink($path.'m_'.$filename);
		$bool = $this->modelDao->where($where)->setField('image','');
		if( $bool!==false ) {
			return true;
		} else {
			return false;
		}
	}
	
	//多选更新排序
	protected function _ordernum() {
		try {
			$ordernums = $_POST ['ordernums'];
			$ids = $_POST ['ids'];
			if (! empty ( $ordernums ) && ! empty ( $ids )) {
				$count = count ( $ids );
				for($i = 0; $i < $count; $i ++) {
					$numAndId = explode ( ',', $ids [$i] );
					$bool = $this->modelDao->setField ( 'ordernum', $ordernums [$numAndId [0] - 1], 'id=' . $numAndId [1] );
				}
			}
			$this->success ( '更新成功！' );
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}
	
	//更新单个字段
	protected function _updateField($filed){
		$fval = $_GET['fval'];
		if($fval=='true') {
			$fval = 1;
		} else {
			$fval = 0;
		}
		echo $this->modelDao->setField ( $filed, $fval, array('id'=>$_GET['id']) );
	}
	
	
	/**
	 * 单个数据表查询分页
	 *
	 * @param array $where 查询条件
	 * @param string $order 排序
	 * @param int $rowpage 每页显示行数
	 */
	protected function _page($where, $rowpage = 20, $sortBy = '', $asc = false) {
	
		//排序字段 默认为主键名
		if (isset($_REQUEST ['_order'])) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = !empty($sortBy) ? $sortBy : $this->accountDao->getPk();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST ['_sort'])) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
			
		/*分页*/
		import ( "ORG.Util.Page" );
		$count = $this->accountDao->where ( $where )->count ();
		$page = new Page ( $count, $rowpage );
		$dataList = $this->accountDao->where ( $where )->order("`" . $order . "` " . $sort)->limit ( $page->firstRow . ',' . $page->listRows )->select ();
	
		/*在URL添加参数*/
		foreach ( $where as $key => $val ) {
			if (!is_array($val)) {
				$p->parameter .= "$key=" . urlencode($val) . "&";
			}
		}
		//$page->setConfig ( "theme", "%first% %upPage% %linkPage% %downPage% %end%" );
		$pageBar = $page->show ();
		$sort = $sort == 'desc' ? 1 : 0; //排序方式
		$this->assign ( 'pageBar', $pageBar );
		$this->assign ( 'sort', $sort);
		$this->assign ( 'totalRows', $count );
		$this->assign ( 'rowpage', $rowpage );
		return $dataList;
	}

}
?>