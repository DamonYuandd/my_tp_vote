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
<h3>网站多语言配置</h3>
<form id="lang_form" action="__APP__/Lang/save" method="post" class="form">
	<input type="hidden" name="id" id="id" value="<?php echo ($obj["id"]); ?>">
    <fieldset>
        <ul class="align-list">
            <li style="height:30px;">
                <div id="msg_lang" style="display:none;line-height:30px;text-align:center;height:30px;color:#fff;">
                </div>
            </li>
			<li>
                <label>当前终端</label>
                <?php 
					$pid = !empty($_GET['pid'])?$_GET['pid']:$obj['pid'];
					if( $_GET['pid']=='3' || $obj['pid']=='3'  ) {
						$deflang = 'pc_def_lang';
						echo '电脑版';
					} else {
						$deflang = 'm_def_lang';
						echo '手机版';
					}
					echo '<input type="hidden" name="pid" value="'.$pid.'">';
				?>
            </li>
            <li>
                <label>语言名称</label>
                <input name="title" id="title" value="<?php echo ($obj["title"]); ?>" class="type-text" style="width:200px">
				<input type="checkbox" name="is_default" value="1"> 默认
            </li>
            <li>
                <label>别名</label>
                <input name="alias" id="alias" value="<?php echo ($obj["alias"]); ?>" class="type-text">
            </li>
            <li>
                <label>描述</label>
				<input name="description" id="description" value="<?php echo ($obj["description"]); ?>" class="type-text">
            </li>
			<li>
                <label>现在发布<a href="#" class="issue" title="在网站前台显示">?</a></label>
                <input type="checkbox" id="is_publish" name="is_publish" value="1">
            </li>
            <li>
                <label>排序</label>
                <input name="ordernum" id="ordernum" value="<?php echo ($obj["ordernum"]); ?>" class="type-text">
            </li>
            <li>
                <label>
                </label>
                <input type="submit" value="保存更改" class="button"/><input type="reset" value="重置" class="button button-red" />
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
		$("input[name=is_default][value=<?php echo $obj['is_default']; ?>]").attr('checked',true);
	} else {
		$("#is_publish").attr("checked",true);
		$("#ordernum").val('30');
	}

	$('#lang_form').ajaxForm({
	    success: function(result){
			if (result == "success_add") {
	            window.optMsg('msg_lang', '添加语言成功！', '#0A8C00');
				parent.goToLeftFrame('__APP__/Account/edit/id/'+account_id+'/tab_id/test2');
	        }else if(result == "error_add") {
	            window.optMsg('msg_lang', '添加语言失败！', 'red');
	        }else if(result == "success_update") {
	            window.optMsg('msg_lang', '更新语言成功！', '#0A8C00');
				parent.goToLeftFrame('__APP__/Account/edit/id/'+account_id+'/tab_id/test2');
	        }else if(result == "error_update") {
	            window.optMsg('msg_lang', '更新语言失败！', 'red');
	        }
	    }
	});
	
	/*//设置默认语言
	$('input[name=pc_def_lang],input[name=m_def_lang]').change(function(){
		var id = $('#id').val();
		var alias = '';
		if($(this).attr('checked')!=undefined) {
			alias = $(this).val();
		}
		if (id != '') {
			$.get('__APP__/Lang/checkedDefault', {'alias': alias, 'id':id, 'field':$(this).attr('name')}, function(result){
				if (result == "success_update") {
					parent.goToLeftFrame('__APP__/Account/edit/id/'+account_id+'/tab_id/test2');
					window.optMsg('msg_lang', '设置默认语言成功！', '#0A8C00');
				} else if (result == "error_update") {
					window.optMsg('msg_lang', '设置默认语言失败！', 'red');
				}
			});
		}
	});*/
	
});

</script>

</body>
</html>