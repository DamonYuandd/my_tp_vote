<?php
//默认语言导航标题
function getDefNavTitle($title_langs,$lang,$title='') {
	if( empty($title_langs) ) {
		echo $title;
	} else {
		if( is_array($title_langs) ) {
			echo $title_langs[$lang]['title'];
		} else {
			echo $title_langs;
		}
	}
}

//checkbox状态
function getCheckboxState($vo_id,$name,$state){
	if($state==1) {
		echo '<input type="checkbox" checked="checked" value="'.$vo_id.'" name="'.$name.'" id="'.$name.'" />';
	} else {
		echo '<input type="checkbox" value="'.$vo_id.'" name="'.$name.'" id="'.$name.'" />';
	}
}

function getModelTitle($alias) {
	switch ($alias) {
		case 'About':
			echo '介绍';
			break;
		case 'News':
			echo '资讯';
			break;
		case 'Goods':
			echo '产品';
			break;
		case 'Guestbook':
			echo '留言';
			break;
		case 'Market':
			echo '网点';
			break;
		case 'Advert':
			echo '广告';
			break;
		case 'Link':
			echo '链接';
			break;
		case 'Joinin':
			echo '加盟';
			break;
		case 'Job':
			echo '招聘';
			break;
		case 'Video':
			echo '视频';
			break;
		case 'Download':
			echo '下载';
			break;
		default:
			return '';
			break;
	}
}

function echoUnitTitle($alias){
	switch ($alias) {
		case 'government':
			echo '政府';
			break;
		case 'enterprise':
			echo '企业';
			break;
		case 'school':
			echo '学校';
			break;
		default:
			return '';
			break;
	}
}


//返回分类标题
function getCategoryTitle($id) {
	$categoryDao = M('Ccategory');
	return $categoryDao->where(array('id'=>$id))->getField('title');
}

//模板使用次数
function echoTplUseCount($number,$hardware) {
	if($number==''||$number=='YZ_test01'||$number=='ytester'||$number=='yy1001'||$number=='MB010'||$number=='MB009'||$number=='MB008'||$number=='MB007'||$number=='MB006'
			||$number=='MB005'||$number=='MB004'||$number=='MB003'||$number=='MB002'||$number=='MB001'||$number=='yy009'||$number=='yy008'||$number=='yy007'
			||$number=='yy006'||$number=='yy005'||$number=='yy004'||$number=='yy003'||$number=='yy002'||$number=='yy001') {
		return false;
	} else {
		if($hardware=='pc') {
			$hardware = 'web';
		}
		$accountDao = M('Account');
		echo $accountDao->where(array($hardware.'_theme'=>$number))->count();
	}
}

function echoStylist($number) {
	$tplDao = M('Template');
	echo $tplDao->where(array('number'=>$number))->getField('stylist');
}

function echoCategoryTitle($id) {
	echo getCategoryTitle($id);
}
