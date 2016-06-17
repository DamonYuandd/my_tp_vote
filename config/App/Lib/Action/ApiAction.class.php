<?php
//开放接口
class ApiAction extends Action
{
	function _initialize() {

	}

	private function _save($data) {
		if( empty($this->account_id)) {
			$data['password'] = md5($data['password']);
			$data['begin_time'] = time();
			$data['end_time'] = strtotime('1 year');
			$data['modules'] = '36,19,20,21,22,23,27,37';
			$data['langs'] = 'mobile,zh-cn';
			$data['username'] = 'admin';
			$data['def_lang'] = 'zh-cn';
			$data['space_mb'] = '3000';
			$data['mobile_theme'] = 'YM04001';
			$data['web_theme'] = 'YP13001';
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
				$data['password'] = md5($data['password']);
				//修改密码同步到对应用户数据库
				$model = M('Admin');
				$adminDao = $model->db(2,'mysql://'.$data['db_user'].':'.$data['db_pwd'].'@'.$data['db_host'].':'.$data['db_port'].'/'.$data['db_name']);
				$adminDao->where(array('username'=>'admin'))->setField('password',$data['password']);
			}
			$data['db_prefix'] = 'y_';
			$result = $this->accountDao->save($data);
			if( $result!==false ) {
				$isConnect = $this->_createDatabase($data);
				exit(json_encode(array('result'=>'success_update','id'=>$data['id'],'isConnect'=>$isConnect)));
			} else {
				exit(json_encode(array('result'=>'error_update')));
			}
		}
	}

}
?>