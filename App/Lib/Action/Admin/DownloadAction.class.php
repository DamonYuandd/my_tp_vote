<?php
/**
 *
 * 下载管理控制器
 * @author uclnn
 *
 */
class DownloadAction extends AdminAction {


	function _initialize() {
		parent::_initialize ();
		$this->c_root = 26;
		$this->setModel('Download');
		$this->assign('c_root', $this->c_root);
	}

	public function index() {
		$cid = $_REQUEST['cid'];
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
			if( $alias=='download_category' ) {
			    $this->downloadCategory($cid);exit;
			}
			$this->_dataPage($categoryDao, $cid, $where);
			
			$this->display ();
		}
	}

	public function downloadCategory( $cid ) {
		$_SESSION [C('USER_AUTH_KEY')]['backUrl'] = __APP__.'/Admin/Download/downloadCategory/cid/'.$cid;//返回列表连接

		$categoryDao = D('Admin.Category');
		$c_root = $categoryDao->getId('download_list', $this->lang);
		$_GET['pid'] = 26;
		$this->assign('c_root', 26);
		$this->assign('titleText', '分类');
		$this->_category();
	}

	public function upload(){
		$dir = $this->upload_root_path.'images/download/';
		$this->_upload2($dir);
	}

	public function edit(){
			
		$this->_edit();
		$this->display ();
	}

	public function add(){
		$_POST['image'] = $this->_imgUploads2('download');
		$this->_add2($_POST);
	}

	public function update(){
		$this->_deleteFile($this->upload_root_path.'download/');
		$_POST['image'] = $this->_imgUploads2('download');
		$this->_update2($_POST);
	}

	public function delete(){
		$this->_delete();
	}

	public function deleteImage() {
		exit($this->_deleteImage($this->upload_root_path.'images/download/'));
	}


	public function deleteById(){
		$this->_deleteFile($this->upload_root_path.'download/');
		$this->_deleteImage($this->upload_root_path.'download/');
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
//批量上传
	function _upload2($dir=''){
			
		// Code for Session Cookie workaround
		if (isset($_POST["PHPSESSID"])) {
			session_id($_POST["PHPSESSID"]);
		} else if (isset($_GET["PHPSESSID"])) {
			session_id($_GET["PHPSESSID"]);
		}

		session_start();

		// Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
		$POST_MAX_SIZE = ini_get('post_max_size');
		$unit = strtoupper(substr($POST_MAX_SIZE, -1));
		$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

		/*if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
		 header("HTTP/1.1 500 Internal Server Error");
		echo "error: 上传的文件过大";
		exit(0);
		}*/
		
		// Settings
		$save_path = $dir;				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
		$upload_name = "Filedata";
		$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
		$extension_whitelist = array("doc", "txt", "jpg", "gif", "png", "zip", "rar", "pdf", "pps","wps");	// Allowed file extensions
		$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)
			
		// Other variables
		$MAX_FILENAME_LENGTH = 260;
		$file_name = "";
		$file_extension = "";
		$uploadErrors = array(
				0=>"文件上传成功",
				1=>"上传的文件超过了 php.ini 文件中的 upload_max_filesize directive 里的设置",
				2=>"上传的文件超过了 HTML form 文件中的 MAX_FILE_SIZE directive 里的设置",
				3=>"上传的文件仅为部分文件",
				4=>"没有文件上传",
				6=>"缺少临时文件夹"
		);

		
		// Validate the upload
		if (!isset($_FILES[$upload_name])) {
			$this->HandleError(":error:No upload found in \$_FILES for " . $upload_name);
			exit(0);
		} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
			$this->HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
			exit(0);
		} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
			$this->HandleError(":error:Upload failed is_uploaded_file test.");
			exit(0);
		} else if (!isset($_FILES[$upload_name]['name'])) {
			$this->HandleError(":error:File has no name.");
			exit(0);
		}
			
		// Validate the file size (Warning: the largest files supported by this code is 2GB)
		$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
		if (!$file_size || $file_size > $max_file_size_in_bytes) {
			//HandleError("File exceeds the maximum allowed size");
			$this->HandleError ( ':error:File exceeds the maximum allowed size');
			exit(0);
		}
		
		if ($file_size <= 0) {
			//HandleError("File size outside allowed lower bound");
			$this->HandleError ( ':error:File size outside allowed lower bound');
			exit(0);
		}
	
		// Validate file name (for our purposes we'll just remove invalid characters)
		$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
		if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
			$this->HandleError(":error:Invalid file name");
			exit(0);
		}


		// Validate that we won't over-write an existing file
		if (file_exists($save_path . $file_name)) {

			$this->HandleError ( ':error:File with this name already exists');
			exit(0);
		}

		// Validate file extension
		$path_info = pathinfo($_FILES[$upload_name]['name']);
		$file_extension = $path_info["extension"];
		//$is_valid_extension = false;
		//foreach ($extension_whitelist as $extension) {
		if (!in_array($file_extension, $extension_whitelist)) {
			//$is_valid_extension = true;
			$this->HandleError ( ':error:文件格式不正确');
			exit;
			//break;
		}
		//}
		/*if (!$is_valid_extension) {

		$this->error ( '异常：Invalid file extension');
		exit(0);
		}*/
		
		$strNewName = rand(10,100).date("YmdHis").'.'.$file_extension;
		if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$strNewName)) {
			 
			$this->HandleError ( ':error:文件无法保存.');
			exit(0);
		}
		
		// Return output to the browser (only supported by SWFUpload for Flash Player 9)
			
		//$arr = array("filename"=>$strNewName);
		echo  ":FILENAME:".$strNewName;
		exit(0);
			
	}
}
?>