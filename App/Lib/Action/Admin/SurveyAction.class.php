<?php
/**
 * 
 * 在线调查管理控制器
 * @author uclnn
 *
 */
class SurveyAction extends AdminAction {
	
	function _initialize() {
	
		parent::_initialize ();
		$this->c_root = 28;
		$this->setModel('Survey');
		$this->assign('c_root', $this->c_root);
	}
	
	public function index() {
		$cid = $_REQUEST['cid'];
		if($cid == '457'){
			$this->result();
			exit;
		}
		
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
	
	public function result(){
		
		$rowpage = $_REQUEST['rowpage'];
		$searchKey = $_REQUEST['searchKey'];
		//$where['lang'] = $this->lang;
		if( !empty($cid) ) {
			$where['category_id'] = $cid;
		}
		
		if( !empty($searchKey) && $searchKey!='请输入关键字' ) {
			$where['_string'] = "title like '%$searchKey%' OR tag like '%$searchKey%'";
		}
		
		
		$rowpage = empty($rowpage)?10:$rowpage;
		$this->assign('dataList', $this->page($where, $rowpage));
		$this->assign('rowpage', $rowpage);
		$this->assign('searchKey', empty($searchKey)?'请输入关键字':$searchKey);
		$this->display ('Survey:result');
	}
	
	public function resList(){
				
		$this->_edit();
		$this->display ('Survey:resList');
	}
	
	public function edit(){
		 
		$this->_edit();
		$this->display ();
	}
		
	public function add(){
		
		$sur_data['title'] = $_POST['title'];
		$sur_data['description'] = $_POST['description'];
		$sur_data['validity_time'] = strtotime($_POST['validity_time']);
		$sur_data['ques_num'] = $_POST['ques_num'];
		$sur_data['my_id'] = $_POST['my_id'];	
		$sur_data['is_publish']	= $_POST['is_publish'];
		$sur_data['one_category_id'] = $_POST['one_category_id'];
				
		try {			
			$data = $this->_processData($sur_data);
			
			if ($this->modelDao->add ( $data )) {
				
				//默认语言添加
				$last_id = $this->modelDao->getLastInsID();
				$this->modelDao->where(array('id'=>$last_id))->setField(array('my_id','lang'),array($last_id,$this->lang));

				if($_POST['ques_title']){
					$sury_ques = D('survey_question');
					$sury_answer = D('survey_answer');
					foreach($_POST['ques_title'] as $key=>$title){
						$ques_data['sort_id'] = $last_id;
						$ques_data['ques_title'] = $title;
						$ques_data['answer_type'] = $_POST['answer_type'.($key+1)] ;
						$sury_ques->add ( $ques_data );
						$ques_id = $sury_ques->getLastInsID();
						if($_POST['answer_title'.($key+1)]){
							foreach($_POST['answer_title'.($key+1)] as $k=>$val){
								$answer_data['ques_id'] = $ques_id;
								$answer_data['answer_title'] = $val;
								$answer_data['answer_type'] = $_POST['answer_type'.($key+1)] ;
								$answer_data['ordernum'] = $_POST['orderid'.($key+1)][$k];
								$sury_answer->add($answer_data);
							}
						}
					}				
				}
				$this->assign("jumpUrl","index");
				$this->success ( '添加成功！', $ajax );
			} else {
				$this->error ( '添加失败！', $ajax );
			}
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}

	public function update(){
		 
		$sur_data['title'] = $_POST['title'];
		$sur_data['description'] = $_POST['description'];
		$sur_data['validity_time'] = strtotime($_POST['validity_time']);
		$sur_data['ques_num'] = $_POST['ques_num'];
		$sur_data['my_id'] = $_POST['my_id'];					
		$sur_data['is_publish']	= $_POST['is_publish'];
		$sur_data['one_category_id'] = $_POST['one_category_id'];
		
		try {
			$sury_ques = D('survey_question');
			$sury_answer = D('survey_answer');
			$this->modelDao->where(array('id'=>$_POST['id']))->save ( $sur_data );
							
			$del_ques_id = explode(',',$_POST['del_ques_id']); //删除的问题ID
			if($del_ques_id){
				foreach($del_ques_id as $del_id){
					$sury_ques->where(array('id'=>$del_id))->delete();
					$sury_answer->where(array('ques_id'=>$del_id))->delete();
				}
				
			}
			
			if($_POST['ques_title']){
												
				if($_POST['ques_title']){
					$quesid_str = explode(',',$_POST['quesid_str']);
					$count = count($quesid_str)-1;
					foreach($_POST['ques_title'] as $key=>$title){
						if(in_array($_POST['ques_id'][$key],$quesid_str) && $key<$count){ //更新问题
							if($_POST['answer_type'.($key+1)]!=3){
								$ques_data['ques_title'] = $title;
								$ques_data['answer_type'] = $_POST['answer_type'.($key+1)] ;														
								$sury_ques->where(array('id'=>$_POST['ques_id'][$key]))->save($ques_data);
								
								if($_POST['answer_title'.($key+1)]){  //更新答案
									$answerlist = $sury_answer->where(array('ques_id'=>$_POST['ques_id'][$key]))->select();
									
									if($answerlist){
										foreach($_POST['answer_title'.($key+1)] as $k=>$val){																													
												
												$answer_data = array();
												$answer_data['answer_title'] = $val;
												$answer_data['answer_type'] = $_POST['answer_type'.($key+1)] ;
												$answer_data['ordernum'] = $_POST['orderid'.($key+1)][$k];											
												
												$sury_answer->where(array('id'=>$_POST['answer_id'.($key+1)][$k]))->save($answer_data);										
										}
									}else{
										foreach($_POST['answer_title'.($key+1)] as $k=>$val){
																																	
												$answer_add = array();
												$answer_add['ques_id'] = $_POST['ques_id'][$key];
												$answer_add['answer_title'] = $val;
												$answer_add['answer_type'] = $_POST['answer_type'.($key+1)] ;
												$answer_add['ordernum'] = $_POST['orderid'.($key+1)][$k];												
												$sury_answer->add($answer_add);											
										}
									}
								}
							}else{ //问题答案由选择答案变输入答案时
								$ques_data['ques_title'] = $title;
								$ques_data['answer_type'] = $_POST['answer_type'.($key+1)] ;														
								$sury_ques->where(array('id'=>$_POST['ques_id'][$key]))->save($ques_data);
								foreach($_POST['answer_title'.($key+1)] as $k=>$val){										
										$sury_answer->where(array('id'=>$_POST['answer_id'.($key+1)][$k]))->delete();
									}																
							}
							
						}else{   //添加新问题
							 
							$ques_add['sort_id'] = $_POST['id'];
							$ques_add['ques_title'] = $title;
							$ques_add['answer_type'] = $_POST['answer_type'.($key+1)] ;							 
							$sury_ques->add ( $ques_add );
							$ques_id = $sury_ques->getLastInsID();
							
							if($_POST['answer_title'.($key+1)]){ //添加新问题的答案
								foreach($_POST['answer_title'.($key+1)] as $k=>$val){
									$answer_data['ques_id'] = $ques_id;
									$answer_data['answer_title'] = $val;
									$answer_data['answer_type'] = $_POST['answer_type'.($key+1)] ;
									$answer_data['ordernum'] = $_POST['orderid'.($key+1)][$k];
									$sury_answer->add($answer_data);
								}
							}
						}
					}
				}								
			}
						 
			$this->success ( '修改成功！', $ajax);
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
				 
	}
	
	public function delete(){
		$this->_delete();
	}
	
	public function deleteById(){
		$this->_deleteById();
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