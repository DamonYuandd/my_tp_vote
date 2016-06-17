<?php

class AccountAction extends CommonAction
{
	function _initialize() {
		$this->account_id = $_REQUEST['id'];
		if( isset($this->account_id) ) {
			$_SESSION['account_id'] = $this->account_id;
		}
		parent::_initialize ();
	}
	
	public function index() {
		$this->display();
	}
	
	public function edit() {
		if( $this->isConnect ) {
			$lang = $this->categoryDao->where(array('pid'=>3,'is_default'=>1))->getField('alias');
			if( empty($lang) ) {
				$lang = $this->categoryDao->where(array('pid'=>4,'is_default'=>1))->getField('alias');
			}
			//echo $this->categoryDao->getlastsql();
			$_SESSION['c_lang'] = $lang;
			$this->assign('moduleList', $this->_getModuleList(12));
			$this->assign('moduleList2', $this->_getModuleList(12,1));
			$this->assign('langList', $this->_getLangList());
			$this->assign('isConnect', true);
		} else {
			$this->assign('isConnect', false);
		}
		$this->display('Index/left');
	}
	
	public function saveWebsite() {
		$data = $_POST;
		if( $this->isConnect==true ) {
			$data['db_user'] = $this->account['db_user'];
			$data['db_pwd'] = $this->account['db_pwd'];
			$data['db_host'] = $this->account['db_host'];
			$data['db_port'] = $this->account['db_port'];
			$data['db_name'] = $this->account['db_name'];
		}
		$this->_save($data);
	}
	
	public function saveDatabase() {
		$_POST['db_user'] = C('DB_USER');
		$_POST['db_pwd'] = C('DB_PWD');
		$this->_save($_POST);
	}
	
	public function saveSpace() {
		$data = $_POST;
		$data['begin_time'] = strtotime($data['begin_time']);
		$data['end_time'] = strtotime($data['end_time']);
		$this->_save($data);
	}
	
	private function _save($data) {
		if( empty($this->account_id)) {
			$data['password'] = md5('admin@y++');
			$data['begin_time'] = time();
			$data['end_time'] = strtotime('1 year');
			//$data['modules'] = '36,19,20,21,22,23,27,37';
			//$data['langs'] = 'mobile,zh-cn';
			$data['username'] = 'admin';
			//$data['def_lang'] = 'zh-cn';
			$data['space_mb'] = '3000';
			//$data['mobile_theme'] = 'YM04001';
			//$data['web_theme'] = 'YP13001';
			$data['url'] = strtolower(','.trim(str_replace("\r\n","",$data['url'])).',');
			$result = $this->accountDao->add($data);
			if( $result!==false ) {
				$last_id = $this->accountDao->getLastInsID();
				$_SESSION['account_id'] = $last_id;
				exit(json_encode(array('result'=>'success_add','id'=>$last_id)));
			} else {
				exit(json_encode(array('result'=>'error_add')));
			}
		} else {
			$data['id'] = $this->account_id;
			if( !empty($data['password']) ) {
				try {
					$data['password'] = md5($data['password']);
					//修改密码同步到对应用户数据库
					$model = M('Admin');
					$adminDao = $model->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
					$adminDao->where(array('username'=>'admin'))->setField('password',$data['password']);
				} catch (Exception $e) {
					exit(json_encode(array('result'=>'error_connent')));
				}
				
			}
			$data['db_prefix'] = 'y_';
			if( !empty($data['url']) ) {
				$data['url'] = strtolower(','.trim(str_replace("\r\n","",$data['url'])).',');
			}
			$result = $this->accountDao->save($data);
			if( $result!==false ) {
				$isConnect = $this->_createDatabase($data);
				exit(json_encode(array('result'=>'success_update','id'=>$data['id'],'isConnect'=>$isConnect)));
			} else {
				exit(json_encode(array('result'=>'error_update')));
			}
		}
	}
	
	//创建快捷方式
	private function _createShortcut($account){
		//$urls = explode(',', $data['url']);
		//$contract_number = $data['url'];
		//if(mkdir("/data/virtualhost/hosou.cn/ytemplate/".$contract_number.'/')){
			//foreach ($urls as $value) {
				$urls = explode(',', $account['url']);
// 				foreach ($urls as $domain) {
// 					if( !empty($domain) ) {
						system("ln -s /data/virtualhost/hosou.cn/ytemplate/ /data/virtualhost/".$urls[1]);
// 					}
// 				}
			//}
		//}
	}
	
	private function _createDatabase($account) {
		$con = $this->_getConnect($account);
		if( $con ) {
			if( mysql_select_db($account['db_name'],$con)==false ) {
				
				if (mysql_query("CREATE DATABASE ".$account['db_name'],$con)) {
					mysql_close($con);
					return $this->_createTable($account);
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	private function _createTable($account) {
		$con = $this->_getConnect($account);
		if( $con ) {
			if(mysql_select_db($account['db_name'], $con)) {
				mysql_query('SET NAMES UTF8');
				$this->sql_query($account);
				$account = $this->_getAccount($account['id']);
				mysql_query("INSERT INTO `y_admin` VALUES ('', '1', '".time()."', '".time()."', '".time()."', '1', '".$account['username']."', '管理员', '".$account['password']."', '127.0.0.1', '', '备注信息');");
				mysql_close($con);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function delete() {
		$id = $_GET['id'];
		if( !empty($id) ) {
			$contract_number = $this->accountDao->where(array('id'=>$id))->getField('contract_number');
			$result = $this->accountDao->where(array('id'=>$id))->delete();
			if( $result!==false ) {
				unlink(C('ACCOUNT_FILE_PATH').'yaccount/'.$contract_number.'_config.php');
				exit('success_delete');
			} else {
				exit('error_delete');
			}
		}
	}
	
	public function isConnect() {
		exit($this->isConnect);
	}
	
	//同步网站名称数据到用户数据
	public function syncWebsiteName() {
		$account = $this->accountDao->where(array('id'=>$_GET['id']))->find();
		$website_name = $account['website_name'];
		$model = new Model();
		//$systemDao = $model->getModel($account);
		//$systemDao = $model->db(3,'mysql://'.$account['db_user'].':'.$account['db_pwd'].'@'.$account['db_host'].':'.$account['db_port'].'/'.$account['db_name']);
		$systemDao = $model->db(1, 'mysql://'.$account['db_user'].':'.$account['db_pwd'].'@'.$account['db_host'].':'.$account['db_port'].'/'.$account['db_name']);
		
		$data['copyright'] = date('Y',time()).'版权所有  '.$website_name;
		
		$bool = $systemDao->query("update y_system set company='$website_name',website='$website_name',copyright='".$data['copyright']."',seo_title='$website_name',seo_keywords='$website_name',seo_description='$website_name'");
		$data['copyright'] = '版权所有@'.$website_name;
		$systemDao->query("update y_system set copyright='".$data['copyright']."' where lang='mobile'");
		if( $bool!==false ) {
			$this->success('同步网站名称成功啦！');
		} else {
			$this->success('同步网站名称失败啦！');
		}
	}
	
	//从V1升级到V2
	function upgradeSite() {
		$account = $this->accountDao->where(array('id'=>$_GET['id']))->find();
		$model = new Model();
		$dao = $model->db(1, 'mysql://'.$account['db_user'].':'.$account['db_pwd'].'@'.$account['db_host'].':'.$account['db_port'].'/'.$account['db_name']);
		
		$dao->query("select weibo_plug,share_plug,is_share,weibo_type from y_system where hardware='mobile' and lang='zh-cn'");
		$bool = $dao->query("update y_common set mobile_theme='".$account['mobile_theme']."',web_theme='".$account['web_theme']."',flow_code='".$account['flow_code']."' where id=1");
		
		$dao->query("update y_category set is_default=0,is_publish=0 where pid=3 or pid=4");
		
		$langs = $account['langs'];
		$langs = explode(',', $langs);
		foreach ($langs as $value) {
			if( $value=='mobile' ) continue;
			$dao->query("update y_category set is_publish=1 where alias='".$value."'");
		}
		$dao->query("update y_category set is_default=1 where alias='".$account['def_lang']."'");
		
		
		
		if( $bool!==false ) {
			$this->success('网站升级成功啦！');
		} else {
			$this->success('网站升级失败啦！');
		}
	}

	
	
	public function downloadConfig() {
		$id = $_GET['id'];
		if( !empty($id) ) {
			$account = $this->accountDao->find($id);
			$db_host = $account['db_host'];
			$db_name = $account['db_name'];
			$db_user = $account['db_user'];
			$db_pwd = $account['db_pwd'];
			$db_port = $account['db_port'];
			$db_prefix = $account['db_prefix'];
			unset($account['db_host'],$account['db_name'],$account['db_user'],$account['db_pwd'],$account['db_port'],$account['db_prefix'],$account['username'],$account['password']);

			$config_content = "<?php\nreturn array(\n'DB_HOST'=>'".$db_host."',\n'DB_NAME'=>'".$db_name."',\n'DB_USER'=>'".$db_user."',\n'DB_PWD'=>'".$db_pwd."',\n'DB_PORT'=>'".$db_port."',\n'DB_PREFIX'=>'".$db_prefix."',\n'ACCOUNT_DIY'=>'".json_encode($account)."',\n);\n?>";
			$file_name = $account['contract_number'].'_config.php';
			$byte = file_put_contents(C('ACCOUNT_FILE_PATH').'yaccount/'.$file_name, $config_content);
			if( $byte>0 ) {
				$this->_createShortcut($account);
				//复制默认图片目录到些合同号目录下
				import('ORG.Io.Dir');
				//Dir::del(C('ACCOUNT_FILE_PATH').'yupload/'.$account['contract_number']);
				Dir::copyDir(C('ACCOUNT_FILE_PATH').'yupload/yy003',C('ACCOUNT_FILE_PATH').'yupload/'.$account['contract_number']);
				$this->success('导出 '.$account['website_name'].' 用户配置成功！');
			} else {
				$this->error('导出 '.$account['website_name'].' 用户配置失败！');
			}
			/*if( $byte>0 ) {
				$file_path = $_SERVER["DOCUMENT_ROOT"].'yaccount/'.$file_name;
				$config_content = "<?php\nreturn array(\n'DB_HOST'=>'".$db_host."',\n'DB_NAME'=>'".$db_name."',\n'DB_USER'=>'".$db_user."',\n'DB_PWD'=>'".$db_pwd."',\n'DB_PORT'=>'".$db_port."',\n'DB_PREFIX'=>'".$db_prefix."',\n);\n?>";
				$byte = file_put_contents($file_path, $config_content);
				if($byte > 0) {
					if(!file_exists($file_path)){
						$this->error('找不到文件');
					}
					$fp=fopen($file_path,"r");
					$file_size=filesize($file_path);
					//下载文件需要用到的头
					Header("Content-type: application/octet-stream");
					Header("Accept-Ranges: bytes");
					Header("Accept-Length:".$file_size);
					Header("Content-Disposition: attachment; filename=".$file_name);
					$buffer=1024;
					$file_count=0;
					//向浏览器返回数据
					while(!feof($fp) && $file_count<$file_size){
						$file_con=fread($fp,$buffer);
						$file_count+=$buffer;
						echo $file_con;
					}
					fclose($fp);
				}
			}*/
		}
		exit;
	}
	
function sql_query($account) {
	$fp = false;
	$query = $this->get_file("sql_file", true);
	if (is_string($query)) { // get_file() returns error as number, fread() as false
		if (function_exists('memory_get_usage')) {
			ini_set("memory_limit", max($this->ini_bytes("memory_limit"), 2 * strlen($query) + memory_get_usage() + 8e6)); // @ - may be disabled, 2 - substr and trim, 8e6 - other variables
		}
		$space = "(?:\\s|/\\*.*\\*/|(?:#|-- )[^\n]*\n|--\n)";
		$delimiter = ";";
		$offset = 0;
		$empty = true;
		include_once 'mysql.inc.php';
		$connection = connect($account); // connection for exploring indexes and EXPLAIN (to not replace FOUND_ROWS()) //! PDO - silent error
		$connection->select_db($account['db_name']);
		$commands = 0;
		$errors = array();
		$line = 0;
		$parse = '[\'"' . ($jush == "sql" ? '`#' : ($jush == "sqlite" ? '`[' : ($jush == "mssql" ? '[' : ''))) . ']|/\\*|-- |$' . ($jush == "pgsql" ? '|\\$[^$]*\\$' : '');
		$total_start = microtime();
		parse_str($_COOKIE["adminer_export"], $adminer_export);
		$dump_format = $this->dumpFormat();
		unset($dump_format["sql"]);
		while ($query != "") {
			if (!$offset && preg_match("~^$space*DELIMITER\\s+(\\S+)~i", $query, $match)) {
				$delimiter = $match[1];
				$query = substr($query, strlen($match[0]));
			} else {
				preg_match('(' . preg_quote($delimiter) . "\\s*|$parse)", $query, $match, PREG_OFFSET_CAPTURE, $offset); // should always match
				list($found, $pos) = $match[0];
				if (!$found && $fp && !feof($fp)) {
					$query .= fread($fp, 1e5);
				} else {
					if (!$found && rtrim($query) == "") {
						break;
					}
					$offset = $pos + strlen($found);
					if ($found && rtrim($found) != $delimiter) { // find matching quote or comment end
						while (preg_match('(' . ($found == '/*' ? '\\*/' : ($found == '[' ? ']' : (ereg('^-- |^#', $found) ? "\n" : preg_quote($found) . "|\\\\."))) . '|$)s', $query, $match, PREG_OFFSET_CAPTURE, $offset)) { //! respect sql_mode NO_BACKSLASH_ESCAPES
							$s = $match[0][0];
							if (!$s && $fp && !feof($fp)) {
								$query .= fread($fp, 1e5);
							} else {
								$offset = $match[0][1] + strlen($s);
								if ($s[0] != "\\") {
									break;
								}
							}
						}
						//echo('-');
					} else { // end of a query
						$empty = false;
						$q = substr($query, 0, $pos);
						$commands++;
						$start = microtime();
						if ($connection->multi_query($q) && is_object($connection) && preg_match("~^$space*USE\\b~isU", $q)) {
							$connection->query($q);
						}
	
						$line += substr_count($q.$found, "\n");
						$query = substr($query, $offset);
						$offset = 0;
					}
				}
			}
		}
	} else {
		
	}
}
	
	function get_file($key, $decompress = false) {
		$return = file_get_contents(C('SQL_FILE_PATH')); //! may not be reachable because of open_basedir
		if ($decompress) {
			$start = substr($return, 0, 3);
			if (function_exists("iconv") && ereg("^\xFE\xFF|^\xFF\xFE", $start, $regs)) { // not ternary operator to save memory
				$return = iconv("utf-16", "utf-8", $return);
			} elseif ($start == "\xEF\xBB\xBF") { // UTF-8 BOM
				$return = substr($return, 3);
			}
		}
		return $return;
	}
	
	function dumpFormat() {
		return array('csv' => 'CSV,', 'csv;' => 'CSV;', 'tsv' => 'TSV');
	}
	
	function ini_bytes($ini) {
		$val = ini_get($ini);
		switch (strtolower(substr($val, -1))) {
			case 'g': $val *= 1024; // no break
			case 'm': $val *= 1024; // no break
			case 'k': $val *= 1024;
		}
		return $val;
	}
}
?>