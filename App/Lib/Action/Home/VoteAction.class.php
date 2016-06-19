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
		
		if (empty($data['codes'])){
			$this->ajaxReturn("","请填写验证码",0);
		}
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
			 
		}else{
			if(!is_numeric($data['age'])){
				$this->ajaxReturn("","请填写正确年龄 ",0);
			}
		}
		if(empty($data['city'])){
			$this->ajaxReturn("","请选择地区",0);
		 
		}
		if(empty($data['teacher'])){
			$this->ajaxReturn("","请填写指导老师",0);
			 
		}
		if(empty($data['entry_mame'])){
			$this->ajaxReturn("","请填写参赛单位名称",0);
			
		}
		if(empty($data['guardian'])){
			$this->ajaxReturn("","请填写作者监护人",0);
			 
		}
		if(empty($data['relation'])){
			$this->ajaxReturn("","请填写与作者关系",0);
			
		}
		if(empty($data['phone'])){
			$this->ajaxReturn("","请填写监护人电话",0);
			
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
			 
		}
		if(empty($data['email'])){
			$this->ajaxReturn("","请填写email",0);
		 
		}else{
			//检验email是否正确
			if(!checkEmail($data['email'])){
				$this->ajaxReturn("","email格式错误，请重填！",0);
			}
			//检查email存在
			$check = M('vote_option')->where(array('email' => $data['email']))->find();
			if($check == true){
				$this->ajaxReturn("","该email已有登记记录",0);
			}
		}
		if(empty($data['avatar'])){
			$this->ajaxReturn("","上传作者头像",0);
		 
		}
		if(empty($data['work_1']) && empty($data['work_2'])){
			$this->ajaxReturn("","上传作品至少一张",0);
			exit;
		}else{
			if($data['work_1']){
				if(empty($data['works_1_w']) || empty($data['works_1_h'])){
					$this->ajaxReturn("","请填写作品1的尺寸",0);
				 
				}else{
					if(!is_numeric($data['works_1_w']) || !is_numeric($data['works_1_h'])){
						$this->ajaxReturn("","请填写正确尺寸",0);
					}
				}
			}
			if($data['work_2']){
				if(empty($data['works_2_w']) || empty($data['works_2_h'])){
					$this->ajaxReturn("","请填写作品2的尺寸",0);
					 
				}else{
					if(!is_numeric($data['works_2_w']) || !is_numeric($data['works_2_h'])){
						$this->ajaxReturn("","请填写正确尺寸",0);
					}
				}
			}
		}
		$total = M('vote_option')->count();
		if($total >= C('TOTAL_NUM')){
			$this->ajaxReturn("","参选作品已经满，请下次参与！！",0);
		}
		
		//开始提交数据
		$data['addTime'] = time();
		$data['author_avatar'] = $data['avatar'];
		$data['works_2'] = $data['work_2'];
		$data['works_1'] = $data['work_1'];
		$obj = M('vote_option')->add($data);
		if($obj){
			$_SESSION['verify'] = null;
			
			$this->ajaxReturn("",json_encode($obj),1);
		}else{
			$this->ajaxReturn("","上传失败",0);
		}
	}
	
	//detail 
	public function detail(){
		if(empty($_GET['id'])){
			$this->error('错误');
		}
		$obj = M('vote_option')->where(array('id' => $_GET['id']))->find();
		
		$this->assign('obj',$obj);
		$this->display($this->web_theme.':Vote:detail');
	}
	
	//入围作品
	public function choiceVote(){
		$order = 'vote_num desc,id desc';
		//a组 书法
		$a1Arr = array('group' => 1,'type' => 1,'isFinalist' => 1);
		$a1 = M('vote_option')->where($a1Arr)->order($order)->select();
		
		//a组 中国画
		$a2Arr = array('group' => 1,'type' => 2,'isFinalist' => 1);
		$a2 = M('vote_option')->where($a2Arr)->order($order)->select();
	    
		
		//a组 其他
		$a3Arr = array('group' => 1,'type' => 3,'isFinalist' => 1);
		$a3 = M('vote_option')->where($a3Arr)->order($order)->select();
		
		
		//b组 书法
		$b1Arr = array('group' => 2,'type' => 1,'isFinalist' => 1);
		$b1 = M('vote_option')->where($b1Arr)->order($order)->select();
		
		//b组 中国画
		$b2Arr = array('group' => 2,'type' => 2,'isFinalist' => 1);
		$b2 = M('vote_option')->where($b2Arr)->order($order)->select();
		
		//b组 其他
		$b3Arr = array('group' => 2,'type' => 3,'isFinalist' => 1);
		$b3 = M('vote_option')->where($b3Arr)->order($order)->select();
		
		
		$this->assign('a1',$a1);
		$this->assign('a2',$a2);
		$this->assign('a3',$a3);
		$this->assign('b1',$b1);
		$this->assign('b2',$b2);
		$this->assign('b3',$b3);
		$this->display($this->web_theme.':Vote:choiceVote');
	}
	
	//投票
	public function voteOption(){
		$data = $_POST;
		
		if (empty($data['codes'])){
			$this->ajaxReturn("","请填写验证码",0);
		}
		//验证码
		if($_SESSION['verify'] != md5($data['codes'])) {
			$this->ajaxReturn("","验证码错误，请注意填写",0);
		}
		if(empty($data['vid'])){
			$this->ajaxReturn("","参数错误",0);
		}
		
		if(empty($data['phone'])){
			$this->ajaxReturn("","请填写电话",0);
				
		}else{
			//检验手机号码是否正确
			if(!checkMobile($data['phone'])){
				$this->ajaxReturn("","手机号码格式错误，请重填！",0);
			}
			//检查号码存在
			$check = M('vote')->where(array('phone' => $data['phone']))->find();
			if($check == true){
				$this->ajaxReturn("","该手机号码已有投票记录",0);
			}
			
			$data['ip'] = getIP();
			$checkIps = M('vote')->where(array('ip' => $data['ip']))->count();
			if($checkIps >= C('IP_NUM') ){
				$this->ajaxReturn("","请不要刷单",0);
			}
		}
		$data['addTime'] = time();
		$obj = M('vote')->add($data);
		if($obj){
			$isUp = M('vote_option')->where(array('id' => $data['vid']))->setInc('vote_num');
			
			if($isUp){
				$_SESSION['verify'] = null;
				$this->ajaxReturn("","操作成功",1);
			}else{
				$this->ajaxReturn("","操作失败",0);
			}			
		}else{
			$this->ajaxReturn("","操作失败",0);
		}
	}
	
	//获奖作品
	public function winner(){
		$order = 'vote_num desc,id desc';
		//a组 书法
		$a1Arr = array('group' => 1,'type' => 1,'isFinalist' => 1,'isAwards' => 1);
		$a1 = M('vote_option')->where($a1Arr)->order($order)->select();
		
		//a组 中国画
		$a2Arr = array('group' => 1,'type' => 2,'isFinalist' => 1,'isAwards' => 1);
		$a2 = M('vote_option')->where($a2Arr)->order($order)->select();
	    
		
		//a组 其他
		$a3Arr = array('group' => 1,'type' => 3,'isFinalist' => 1,'isAwards' => 1);
		$a3 = M('vote_option')->where($a3Arr)->order($order)->select();
		
		
		//b组 书法
		$b1Arr = array('group' => 2,'type' => 1,'isFinalist' => 1,'isAwards' => 1);
		$b1 = M('vote_option')->where($b1Arr)->order($order)->select();
		
		//b组 中国画
		$b2Arr = array('group' => 2,'type' => 2,'isFinalist' => 1,'isAwards' => 1);
		$b2 = M('vote_option')->where($b2Arr)->order($order)->select();
		
		//b组 其他
		$b3Arr = array('group' => 2,'type' => 3,'isFinalist' => 1,'isAwards' => 1);
		$b3 = M('vote_option')->where($b3Arr)->order($order)->select();
		
		
		$this->assign('a1',$a1);
		$this->assign('a2',$a2);
		$this->assign('a3',$a3);
		$this->assign('b1',$b1);
		$this->assign('b2',$b2);
		$this->assign('b3',$b3);
		$this->display($this->web_theme.':Vote:winner');
	}

}
?>