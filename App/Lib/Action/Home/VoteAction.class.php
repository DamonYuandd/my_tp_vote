<?php
/**
 *
 * 首页控制器
 * @author uclnn
 *
 */
class VoteAction extends HomeAction
{
	function _initialize() {
		parent::_initialize();
	}

	public function index() {	
		$grouplist = M('group')->select();
		$typelist = M('type')->select();
		$citylist = M('city')->select();
		
		$this->assign('citylist',$citylist);
		$this->assign('typelist',$typelist);
		$this->assign('grouplist',$grouplist);
		$this->display($this->web_theme.':Vote:index');
	}
	
	//申请投票项
	public function application(){
		$data = $_POST;
		//验证码
		if($_SESSION['verify'] != md5($data['codes'])) {
			$this->ajaxReturn("","验证码错误，请注意填写",0);
		}
		if(empty($data['group'])){
			$this->ajaxReturn("","请选择组别",0);
		}
		if(empty($data['type'])){
			$this->ajaxReturn("","请选择类型",0);
		}
		if(empty($data['title'])){
			$this->ajaxReturn("","请填写作品名称 ",0);
		}
		if(empty($data['name'])){
			$this->ajaxReturn("","请填写作者名称 ",0);
		}
		if(empty($data['age'])){
			$this->ajaxReturn("","请填写年龄 ",0);
			exit;
		}
		if(empty($data['city'])){
			$this->ajaxReturn("","请选择地区",0);
			exit;
		}
		if(empty($data['teacher'])){
			$this->ajaxReturn("","请填写指导老师",0);
			exit;
		}
		if(empty($data['entry_mame'])){
			$this->ajaxReturn("","请填写参赛单位名称",0);
			exit;
		}
		if(empty($data['guardian'])){
			$this->ajaxReturn("","请填写作者监护人",0);
			exit;
		}
		if(empty($data['relation'])){
			$this->ajaxReturn("","请填写与作者关系",0);
			exit;
		}
		if(empty($data['phone'])){
			$this->ajaxReturn("","请填写监护人电话",0);
			exit;
		}else{
			//检验手机号码是否正确
			if(!checkMobile($data['phone'])){
				$this->ajaxReturn("","手机号码格式错误，请重填！",0);
			}
			//检查号码存在
			$check = M('vote_option')->where(array('phone' => $data['phone']))->find();
			if($check == true){
				$this->ajaxReturn("","该手机号码已有登记记录",0);
			}
		}
		
		if(empty($data['address'])){
			$this->ajaxReturn("","请填写联系地址",0);
			exit;
		}
		if(empty($data['email'])){
			$this->ajaxReturn("","请填写email",0);
			exit;
		}
		if(empty($data['avatar'])){
			$this->ajaxReturn("","上传作者头像",0);
			exit;
		}
		if(empty($data['work_1']) && empty($data['work_2'])){
			$this->ajaxReturn("","上传作品至少一张",0);
			exit;
		}else{
			if($data['work_1']){
				if(empty($data['works_1_w']) || empty($data['works_1_h'])){
					$this->ajaxReturn("","请填写作品1的尺寸",0);
					exit;
				}
			}
			if($data['work_2']){
				if(empty($data['works_2_w']) || empty($data['works_2_h'])){
					$this->ajaxReturn("","请填写作品2的尺寸",0);
					exit;
				}
			}
		}
		
		
		//开始提交数据
		$data['addTime'] = time();
		$data['author_avatar'] = $data['avatar'];
		$data['works_2'] = $data['work_2'];
		$data['works_1'] = $data['work_1'];
		$obj = M('vote_option')->add($data);
		if($obj){
			$_SESSION['verify'] = null;
			$this->ajaxReturn("","上传成功",1);
		}else{
			$this->ajaxReturn("","上传失败",0);
		}
		
		//dump($_POST);
	}

}
?>