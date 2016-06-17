<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/base.css" />
<script src="__PUBLIC__/js/jquery-1.7.1.min.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script src="__PUBLIC__/js/base.js"></script>
<title>edit</title>
</head>
<body>
<h3>系统功能定制</h3>
<form id="category_form" action="__APP__/Category/save" method="post" class="form">
    <input type="hidden" name="id" value="<?php echo ($obj["id"]); ?>">
	<input type="hidden" name="pid" value="12">
	<input type="hidden" name="hardware" value="">
	<input type="hidden" name="lang" value="">
	<input type="hidden" name="tpl_one" value="auto">
    <fieldset>
        <ul class="align-list">
            <li style="height:30px;">
                <div id="msg_category" style="display:none;line-height:30px;text-align:center;height:30px;color:#fff;">
                </div>
            </li>
			<li>
                <label>导航模型</label>
                <select id="module" name="module" style="padding:7px;">
					<option value="About">介绍 --> 单页内容介绍</option>
					<option value="News">资讯 --> 新闻列表显示</option>
					<option value="Goods">产品 --> 列表带多图片展示</option>
					<option value="Guestbook">留言 --> 回答客户问题的功能</option>
					<option value="Market">网点 --> 多个分公司地图显示</option>
					<option value="Advert">广告 --> 首页滚动横幅和内页横幅管理</option>
					<option value="Link">链接 --> 标题带有外连接跳转</option>
					<option value="Joinin">加盟 --> 提供加盟信息内容格式显示</option>
					<option value="Job">招聘 --> 提供招聘信息内容格式显示</option>
					<option value="Video">视频 --> 可以在网站上放视频</option>
					<option value="Download">下载 --> 可供客户下载文件</option>
					<!--option value="Survey">调查</option>
					<option value="Member">会员</option-->
                </select>
            </li>
            <li>
                <label>栏目标题<input type="hidden" name="langs[]" value="<?php echo ($langList["0"]["alias"]); ?>"></label>
                <input name="titles[]" value="<?php getDefNavTitle($title_langs,$_SESSION['c_lang']);?>" class="type-text">
            </li>
			<?php if(is_array($langList)): $i = 0; $__LIST__ = array_slice($langList,1,100,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
				<label><input type="hidden" name="langs[]" value="<?php echo ($vo["alias"]); ?>"></label>
				
				<span style="color:#ddd">|--</span><span style="color:#BB4141"><?php echo ($vo["title"]); ?></span> <input name="titles[]" value="<?php getDefNavTitle($title_langs,$vo['alias']);?>" class="type-text" style="width: 568px;">
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
            <li>
                <label>别名 <a href="#" class="issue" title="指的是前台URL路径,如News/index">?</a></label>
                <input name="alias" id="alias" value="<?php echo ($obj["alias"]); ?>" class="type-text">
            </li>
			<li>
                <label>是否导航<a href="#" class="issue" title="在前台导航栏显示">?</a></label>
                <input type="checkbox" id="is_nav" name="is_nav" value="1">
            </li>
            <li>
                <label>前台自定义URL<a href="#" class="issue" title="例如：News/index/cid/120">?</a></label>
                <input name="furl" id="furl" value="<?php echo ($obj["furl"]); ?>" class="type-text">
            </li>
            <li>
                <label>后台自定义URL<a href="#" class="issue" title="例如：User/index/cid/31">?</a></label>
                <input name="burl" id="burl" value="<?php echo ($obj["burl"]); ?>" class="type-text">
            </li>
			<li>
                <label>描述</label>
				<input name="description" id="description" value="<?php echo ($obj["description"]); ?>" class="type-text">
            </li>
            <li>
                <label>排序<a href="#" class="issue" title="使用倒序排列">?</a></label>
                <input name="ordernum" id="ordernum" value="<?php echo ($obj["ordernum"]); ?>" value="10" class="type-text" style="width:100px"> <em>提示：数字最大排最前（关联到前后台排序）</em>
            </li>
            <li>
                <label>现在发布<a href="#" class="issue" title="在网站前台显示">?</a></label>
                <input type="checkbox" id="is_publish" name="is_publish" value="1">
            </li>
            <li>
                <label></label>
				<input type="submit" value="保存导航" class="button button-green" id="add_button" />
				<input type="reset" value="重置" class="button button-red" />
            </li>
        </ul>
    </fieldset>
</form>

<script>
$(function(){
	
	var account_id = '<?php echo $_GET["account_id"];?>';
	var obj_id = "<?php echo $obj['id']; ?>";
	
	if( obj_id!='' ) {
		$("input[name=is_publish][value=<?php echo $obj['is_publish']; ?>]").attr('checked',true);
		$("input[name=is_nav][value=<?php echo $obj['is_nav']; ?>]").attr('checked',true);
		$("#module").val("<?php echo $obj['module']; ?>");
	} else {
		$("#is_publish").attr("checked",true);
		$("#is_nav").attr("checked",true);
		$("#ordernum").val('30');
	}
	
	jQuery('#category_form').ajaxForm({
		dataType: 'json',
		beforeSubmit: function(arr, $form, options) {
			if( jQuery('#title').val()=='' ) {
				window.optMsg('msg_category', '请输入栏目标题！', 'red');
				return false;
			}
		},
	    success: function(json){
			var pid = jQuery('#pid').val();
			if (json.result == "success_add") {
				window.optMsg('msg_category', '创建栏目成功！', '#0A8C00');
				parent.goToLeftFrame('__APP__/Account/edit/id/'+account_id+'/tab_id/test3');
	        }else if(json.result == "error_add") {
				window.optMsg('msg_category', '创建栏目失败！', 'red');
	        }else if(json.result == "success_update") {
				window.optMsg('msg_category', '更新栏目成功！', '#0A8C00');
				parent.goToLeftFrame('__APP__/Account/edit/id/'+account_id+'/tab_id/test3');
	        }else if(json.result == "error_update") {
				window.optMsg('msg_category', '更新栏目失败！', 'red');
	        }
	    }
	});
	
});

</script>

</body>
</html>