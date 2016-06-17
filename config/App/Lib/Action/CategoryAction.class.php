<?php

class CategoryAction extends CommonAction
{
	function _initialize() {
		parent::_initialize ();
	}
	
	public function index() {
		$this->display();
	}
	
	public function edit() {
		$id = $_GET['id'];
		$aid = $_GET['aid'];
		$act = $_GET['act'];
		if( !empty($id) ) {
			$obj = $this->categoryDao->find($id);
			$categoryList = $this->_getModuleList($obj['pid']);
			$this->assign('pid', $obj['pid']);
			$this->assign('hardware', $obj['hardware']);
		}
		if( $act=='update' ) {
			$this->assign('obj', $obj);
		}
		$this->assign('categoryList', $categoryList);
		$this->assign('langList', $this->_getCheckedLangList());
		$this->display();
	}
	
	public function editNav() {
		$id = $_GET['id'];
		if( !empty($id) ) {
			$obj = $this->categoryDao->find($id);
			$this->assign('title_langs', json_decode($obj['title'],true));
			$this->assign('obj', $obj);
		}
		$this->assign('langList', $this->_getCheckedLangList());
		$this->display('edit_nav');
	}
	
	public function save() {
		$data = $_POST;
		if (!isset($data['is_publish'])) {
			$data['is_publish'] = 0;
		}
		if (!isset($data['is_nav'])) {
			$data['is_nav'] = 0;
		}
		$levels = $this->categoryDao->getField('levels', array('id'=>$data['pid']));
		if( empty($levels) ) {
			$data['levels'] = $data['pid'];
		} else {
			$data['levels'] = $levels.'|'.$data['pid'];
		}
		
		$langs = $data['langs'];
		$titles = $data['titles'];
		if( !empty($titles) && !empty($langs) ) {
			$langs_count = count($langs);
			for ($i = 0; $i < $langs_count; $i++) {
				$titleArr[$langs[$i]]['title'] = $titles[$i];
			}
			$data['title'] = json_encode($titleArr);
		}
		if( empty($data['id'])) {
			$result = $this->categoryDao->add($data);
			if( $result!==false ) {
				exit(json_encode(array('result'=>'success_add')));
			} else {
				exit(json_encode(array('result'=>'error_add')));
			}
		} else {
			$result = $this->categoryDao->save($data);
			if( $result!==false ) {
				exit(json_encode(array('result'=>'success_update')));
			} else {
				exit(json_encode(array('result'=>'error_update')));
			}
		}
	}
	
	public function delete() {
		$id = $_GET['id'];
		if( !empty($id) ) {
			$result = $this->categoryDao->delete($id);
			if( $result!==false ) {
				exit(json_encode(array('result'=>'success_delete')));
			} else {
				exit(json_encode(array('result'=>'error_delete')));
			}
		}
	}
	
	public function checkedModule() {
		$id = $_GET['id'];
		$modules = $_GET['modules'];
		if( !empty($id) && !empty($modules)) {
			$this->accountDao->setField('modules', implode(',', $modules), array('id'=>$id));
			foreach ($modules as $key => $value) {
				$data[$key]['id'] = $value;
				$data[$key]['title'] = $this->categoryDao->getField('title', array('id'=>$value));
			}
			exit(json_encode( $data ) );
		} else {
			exit(json_encode( '' ) );
		}
	}
	
	public function selectCategoryByPid() {
		$id = $_GET ["id"];
		$json ['list'] = $this->_getCategoryLangTitleList($id);
		exit(json_encode ( $json ));
	}
	
	public function selectLangs() {
		$categoryList = $this->categoryDao->where(array('pid'=>$_GET['pid'],'is_publish'=>1))->select();
		exit(json_encode ( $categoryList ));
	}
	
	public function part(){
		$this->getPartList();
		$this->getLang();
		$this->display();
	}
	//获取对应语言的栏目
	public function getPartList(){
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		if($_POST['lang']){
			$lang = $_POST['lang'];
		}else{
			$lang = 'zh-cn';
		}
		$Dao = M('Part');
		$partDao = $Dao->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
		//$part = $partDao->where(array('username'=>'admin'))->setField('password',$data['password']);
		$part = $partDao->where(array('lang'=>$lang,'pid'=>0))->order('orderNum desc')->select();
		for($i=0 ;$i<count($part) ;$i++ ){
			$getNext = $partDao->where(array('lang'=>'zh-cn','pid'=>$part[$i]['id']))->order('orderNum desc')->select();
			$part[$i]['next'] = $getNext;
		}
		if($this->isAjax()){
			echo json_encode($part);
		}else{
			$this->assign('part',$part);
		}
	}
	//某条栏目信息
	public function addPart(){
		if($_GET['id']){	//查看某条信息
			$this->getPartInfo($_GET['id']);
		}
		else{	//添加
		
		$this->pidList('zh-cn'); //获取下拉
		$this->getLang(); //获取语言
		}
		$this->display();
	}
	//获取语言
	public function getLang(){
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		$Dao = M('Category');
		$cDao = $Dao->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
		$this->assign('langList', $cDao->where(array('pid'=>11 ,'is_publish'=>1))->select());
	}
	//获取顶级栏目
	function pidList($lang){
		if(!$lang){ $lang = $_POST['lang'];}
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		$Dao = M('Part');
		$partDao = $Dao->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
		$list = $partDao->where(array('lang' => $lang,'pid' => 0))->select();
		for($i=0 ; $i<count($list);$i++){
			$option .= "<option value=".$list[$i]['id'].">".$list[$i]['title']."</option>";
		}
		$i = "<option value=\"0\">请选择</option>".$option;
		if($this->isAjax()){
			echo $i;
		}else{
			$this->assign('categoryList',$i);
		}
	}
	
	//添加	
	function savePart(){
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		$Dao = M('Part');
		$partDao = $Dao->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
		//添加
		$result = $partDao->add($_POST);
		if($result){
			$this->success ( '添加成功！' );
		}else{
			$this->error ( '异常' );
		}
		
	}
	//更新栏目
	function undatePart(){
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		$Dao = M('Part');
		$partDao = $Dao->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
		//修改
		if(!$_POST['is_publish'])
		{ $_POST['is_publish'] = 0;}
		$result = $partDao->where('id='.$_POST['id'])->save($_POST);
		if($result){
			$this->success ( '添加成功！' );
		}else{
			$this->error ( '异常' );
		}
	}
	//获取某条栏目信息
	function getPartInfo($id){
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		$Dao = M('Part');
		$partDao = $Dao->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
		$result = $partDao->where('id = '.$id)->find();
		$this->pidList($result['lang']);
		$this->getLang();
		$this->assign('obj',$result);
		//添加
	}
	//删除
	function deletePart(){
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		$Dao = M('Part');
		$partDao = $Dao->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
		$result = $partDao->where(array('id' => $_POST['id']))->delete();
		if($result)
		{
			echo '1';
			//echo json_encode($result);
		}else{
			echo '0';
		}
	}
}
?>