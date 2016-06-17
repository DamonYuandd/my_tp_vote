<?php

class TemplateAction extends CommonAction
{
	
	function _initialize() {
		parent::_initialize();
		$this->setModel('Template');
	}
	
	public function index() {
		
		if( !empty($_GET['hardware']) ) {
			$where['hardware'] = array('in',$_GET['hardware']);
			$this->assign('hardware',$_GET['hardware']);
		} else {
			$where['hardware'] = array('in','pc');
			$this->assign('hardware','pc');
		}
		$this->assign('templateList', $this->modelDao->where($where)->order('ordernum desc')->select());
		
		$this->display();
	}
	
	public function edit() {
		$id = $_GET['id'];
		if( !empty($id) ) {
			$template = $this->modelDao->find($id);
			$this->assign('obj', $template);
		}
		$categoryDao = M('Ccategory');
		$this->assign('categoryList',$categoryDao->where(array('pid'=>100))->select());
		$this->display();
	}
	
	public function save() {
		$data = $_POST;
		if( empty($data['id']) ) {
			$data['update_time'] = time();
			$data['create_time'] = time();
			$bool = $this->modelDao->add($data);
			if( $bool!==false ) {
				$last_id = $this->modelDao->getLastInsID();
				$this->_uploadTplFile($last_id);
				$this->success('添加模板成功！');
			} else {
				$this->error('添加模板失败！');
			}
		} else {
			$bool = $this->modelDao->save($data);
			if( $bool!==false ) {
				$this->_uploadTplFile($data['id']);
				$this->success('修改模板成功！');
			} else {
				$this->error('修改模板失败！');
			}
		}
	}
	
	private function _uploadTplFile($tid ) {
		$imgwidth = $_POST['imgwidth'];
		$imgheight = $_POST['imgheight'];
		$tpl_file_path = C('ACCOUNT_FILE_PATH').'yupload/tplimg/';
		$where['id'] = $tid;
		if( !empty($_FILES['image']['name']) && !empty( $imgwidth ) && !empty( $imgheight ) ) {
			$this->_uploadImg($tpl_file_path, 'image', false, $_POST['number'], $imgwidth, $imgheight);
			$this->modelDao->where($where)->setField('image', $_POST['image']);
		}
	}

	public function deleteImage() {
		exit($this->_deleteImage(C('ACCOUNT_FILE_PATH').'yupload/tplimg/'));
	}
	
	public function delete(){
		try {
			$id = $_GET ['id'];
			if( !empty($id) ) {
				$filename = $this->modelDao->where(array('id'=>$id))->getField('image');
				$bool = $this->modelDao->delete ( $id );
				if( $bool!==false ) {
					unlink(C('ACCOUNT_FILE_PATH').'yupload/tplimg/'.$filename);
					$this->success ( '删除成功！' );
				} else {
					$this->error ( '删除失败！' );
				}
			}
		} catch ( Exception $e ) {
			$this->error ( '异常：' . $e->getMessage () );
		}
	}
	
	public function ordernum(){
		$this->_ordernum();
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