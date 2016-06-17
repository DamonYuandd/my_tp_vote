<?php
/**
 *
 * 首页控制器
 * @author uclnn
 *
 */
class IndexAction extends HomeAction
{
	function _initialize() {
		parent::_initialize();
	}
	public function index() {	
		
		$this->display($this->web_theme.':Index:index');
	}
	//验证码生成
	public function verify() {
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'gif';
		import ( "ORG.Util.Image" );
		Image::buildImageVerify ( 4, 1, $type );
	}
	
	public function upload(){
	
	
		if (!empty($_FILES)) {
			$name=time();
			import("ORG.Net.UploadFile");
	
			$upload = new UploadFile();// 实例化上传类
	
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
	
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	
			$upload->savePath =  './Public/Uploads/'.date('Ymd',time()).'/';// 设置附件上传目录
	
			$upload->saveRule =  $name;
	
			if(!$upload->upload()) {// 上传错误提示错误信息
	
	
				$info=$upload->getErrorMsg();
				
				echo '<script>parent.error("'.$info.'")</script>';
	
				
	
			}else{// 上传成功 获取上传文件信息
	
				$info =  $upload->getUploadFileInfo();
	
				//$url=$upload->savePath.$info[0]['savename'];
	
				$url= date('Ymd',time()).'/'.$info[0]['savename'];
				echo '<script>parent.add("'.$url.'","'.$_GET['position'].'")</script>';
	
			}
		}
		else{
			echo '<script>parent.error("网络问题上传失败，请重新上传！！")</script>';
		}
	}
}
?>